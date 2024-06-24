<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \Kreait\Firebase\Auth */
        $auth = app('firebase.auth');
        $id_token = $request->headers->get('Authorization');

        try {
            $verified_id_token = $auth->verifyIdToken($id_token);
        } catch (\Exception $e) {
            return response(status: 401);
        }

        // dd($verified_id_token->claims(), $verified_id_token->claims()->get('sub'));

        $uid = $verified_id_token->claims()->get('sub');

        if (!$uid) {
            return response(status: 401);
        }

        // $name = $verified_id_token->claims()->get('name');
        $name = $request->username ?? '名無しの権平';

        /** @var User */
        $user = User::firstOrNew([
            'firebase_id' => $uid
        ]);

        /** 新規ユーザの場合は name をセット */
        if (!$user->name) {
            $user->name = $name;
        }

        $user->save();

        return new JsonResource(
            $user
        );
    }
}
