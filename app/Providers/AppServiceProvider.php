<?php

namespace App\Providers;

use App\Models\User;
use App\Utils\MockIdTokenVerify;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        DB::listen(static function (QueryExecuted $event) {
            $sql = $event->connection
                ->getQueryGrammar()
                ->substituteBindingsIntoRawSql(
                    sql: $event->sql,
                    bindings: $event->connection->prepareBindings($event->bindings),
                );

            if ($event->time > 1000) {
                Log::warning(sprintf('%.2f ms, SQL: %s;', $event->time, $sql));
            } else {
                Log::debug(sprintf('%.2f ms, SQL: %s;', $event->time, $sql));
            }
        });

        Event::listen(static fn (TransactionBeginning $event) => Log::debug('START TRANSACTION'));
        Event::listen(static fn (TransactionCommitted $event) => Log::debug('COMMIT'));
        Event::listen(static fn (TransactionRolledBack $event) => Log::debug('ROLLBACK'));
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
