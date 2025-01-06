<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        return DB::transaction(function () use ($request) {
                /** @var \Kreait\Firebase\Auth */
            $auth = app('firebase.auth');

            try {
                $firebaseUser = $auth->getUserByEmail($request->email);
                if (!$firebaseUser->emailVerified) {
                    $auth->deleteUser($firebaseUser->uid);
                }
            } catch (\Exception $e) {
                //
            }

            $firebaseUser = $auth->createUserWithEmailAndPassword($request->email, $request->password);

            /** @var User */
            $user = User::firstOrNew([
                'firebase_id' => $firebaseUser->uid
            ]);
            $user->name = $request->username;
            $user->save();

            return new AuthUserResource(
                $user
            );
        });
    }


    public function auth()
    {
        return new AuthUserResource(
            Auth::user()
        );
    }
}
