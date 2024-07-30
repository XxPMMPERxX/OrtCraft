<?php

namespace App\Providers;

use App\Models\User;
use App\Utils\MockIdTokenVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::viaRequest('firebase', function (Request $request) {
            $auth = app('firebase.auth');

            $id_token = $request->headers->get('Authorization');

            try {
                $verified_id_token = app()->isProduction()
                ? $auth->verifyIdToken($id_token) : MockIdTokenVerify::verifyIdToken($id_token);
            } catch (\Exception $e) {
                return null;
            }

            $uid = $verified_id_token->claims()->get('sub');

            /** @var \App\Models\User */
            $user = User::where('firebase_id', $uid)->firstOrFail();
            /**
             * firebaseのクレームを付与
             */
            $user->firebase_claims = $verified_id_token->claims();

            return $user;
        });
    }
}
