<?php

namespace App\Providers;

use App\Models\User;
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
                $verified_id_token = $auth->verifyIdToken($id_token);
            } catch (\Exception $e) {
                return null;
            }
            
            $uid = $verified_id_token->claims()->get('sub');
            return User::where('firebase_id', $uid)->firstOrFail();
        });
    }
}
