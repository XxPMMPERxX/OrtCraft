<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Enums\ServerMemberRole;
use App\Enums\ServerPlatformType;
use App\Facades\Auth;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserResource;
use App\Models\Server;
use App\Models\User;
use App\Notifications\FriendRequest;
use App\Notifications\MemberInvitation;
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


    /**
     * フレンド申請送信
     */
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


    /**
     * フレンド申請承認
     */
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


    /**
     * フレンド一覧取得
     */
    public function getFriends()
    {
        return new ResourceCollection(Auth::user()->friends);
    }


    /**
     * メンバー招待送信
     */
    public function sendMemberInvitation(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $to = $request->to;
            $serverId = $request->server_id;

            $server = Server::findOrFail($serverId);

            if (!$server->members()->where('users.id', $user->id)->exists()) {
                return response()->json([], 403);
            }

            if (!$to || $user->id === $to) {
                return response()->json([], 400);
            }

            /** @var User */
            $to = User::findOrFail($to);

            if ($server->members()->where('users.id', $to->id)->exists()) {
                return response()->json([
                    'message' => '既にメンバーです。',
                ], 409);
            }

            $memberInvitations = $to->notifications
                ->where('type', NotificationType::MemberInvitation->value);

            if ($memberInvitations
                ->map
                ->data
                ->where('server_id', $serverId)
                ->count()  > 0) {
                return response()->json([
                    'message' => '既に招待済みです。',
                ], 409);
            }

            $to->notify(
                new MemberInvitation($server, $to)
            );
        });
    }


    /**
     * メンバー招待承認
     */
    public function approveMemberInvitation(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();

            $notificationId = $request->notificationId;

            /** @var DatabaseNotification */
            $notification = $user->notifications()->findOrFail($notificationId);

            if ($notification->type !== NotificationType::MemberInvitation->value) {
                return response()->json([], 400);
            }

            /** @var array{title:string, sender_id:string, receiver_id:string, server_id:string} */
            $data = $notification->data;

            if ($data['receiver_id'] !== $user->id) {
                return response()->json([], 403);
            }

            $receiver = User::findOrFail($data['receiver_id']);
            $server = Server::findOrFail($data['server_id']);


            if ($server->members()->where('users.id', $receiver->id)->exists()) {
                $notification->delete();
                return response()->json([
                    'message' => '既にメンバーです。',
                ], 409);
            }

            // 権限者として追加
            $server->members()->attach($receiver, ['user_role' => ServerMemberRole::ADMIN]);

            $notification->delete();
        });
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
