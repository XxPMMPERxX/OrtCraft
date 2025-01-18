<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Enums\ServerPlatformType;
use App\Facades\Auth;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\FriendRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function index(Request $request)
    {
        return UserResource::collection(
            User::search($request->all())
                ->get()
                ->filter(function (User $user) {
                    // メール認証がまだのユーザは出さない
                    return $user->firebaseUser?->emailVerified;
                })
        );
    }

    /**
     * マイクラ連携を解除
     */
    public function cancelAuth(Request $request)
    {
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $platform = ServerPlatformType::tryFrom($request->platform);

            switch ($platform) {
                case ServerPlatformType::JAVA:
                    $user->minecraft_java_uid = null;
                    $user->minecraft_java_gamertag = null;
                    break;
                case ServerPlatformType::BE:
                    $user->minecraft_be_uid = null;
                    $user->minecraft_be_gamertag = null;
                    break;
            }
            $user->save();

            return new JsonResource(
                $user
            );
        });
    }


    public function sendFriendRequest(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $to = $request->to;

            if (!$to || $user->id === $to) {
                return response()->json([], 400);
            }

            /** @var User */
            $to = User::findOrFail($to);

            if ($to->is_friend) {
                return response()->json([
                    'message' => '既に友達です。',
                ], 409);
            }

            if ($to->already_sent_friend_request > 0) {
                return response()->json([
                    'message' => '既に申請済みです。',
                ], 409);
            }

            $to->notify(
                new FriendRequest($to)
            );
        });
    }


    public function approveFriendRequest(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            $notificationId = $request->notificationId;

            /** @var DatabaseNotification */
            $notification = $user->notifications()->findOrFail($notificationId);

            if ($notification->type !== NotificationType::FriendRequest->value) {
                return response()->json([], 400);
            }

            /** @var array{title:string, sender_id:string, receiver_id:string} */
            $data = $notification->data;

            if ($data['receiver_id'] !== $user->id) {
                return response()->json([], 403);
            }

            if (User::find($data['sender_id'])->is_friend) {
                $notification->delete();
                return response()->json([
                    'message' => '既に友達です。',
                ], 409);
            }

            DB::table('friends')
                ->insert([
                    [
                        'user_id_1' => $data['sender_id'],
                        'user_id_2' => $data['receiver_id'],
                    ],
                    [
                        'user_id_1' => $data['receiver_id'],
                        'user_id_2' => $data['sender_id'],
                    ],
                ]);

            $notification->delete();
        });
    }


    public function getFriends()
    {
        return new ResourceCollection(Auth::user()->friends);
    }


    public function getNotifications(Request $request)
    {
        $notifications = Auth::user()->notifications()
            ->paginate($request->items_per_page ?? -1);
        $notifications->markAsRead();

        return NotificationResource::collection($notifications);
    }

    public function checkNotification(Request $request)
    {
        return new JsonResource([
            'has_new_notification' => Auth::user()->unreadNotifications()->exists(),
        ]);
    }
}
