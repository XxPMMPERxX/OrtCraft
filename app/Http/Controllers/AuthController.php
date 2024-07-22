<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Utils\MockIdTokenVerify;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        /** @var \Kreait\Firebase\Auth */
        $auth = app('firebase.auth');
        $id_token = $request->headers->get('Authorization');


        try {
            /**
             * 開発環境の場合はモックのidToken検証を行う
             */
            $verified_id_token = app()->isProduction()
                ? $auth->verifyIdToken($id_token) : MockIdTokenVerify::verifyIdToken($id_token);
        } catch (\Exception $e) {
            return response(status: 401);
        }

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


    public function auth()
    {
        return new JsonResource(
            Auth::user()
        );
    }
}
