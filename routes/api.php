<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MinecraftAuthController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', [TestController::class, 'index']);
Route::post('/user/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/auth', [AuthController::class, 'auth']);
    Route::post('/minecraft-auth', MinecraftAuthController::class);
    Route::delete('/minecraft-auth/cancel', [UserController::class, 'cancelAuth']);

    Route::post('/send-friend-request', [UserController::class, 'sendFriendRequest']);
    Route::post('/approve-friend-request', [UserController::class, 'approveFriendRequest']);
    Route::get('/friends', [UserController::class, 'getFriends']);

    Route::post('/send-member-invitation', [UserController::class, 'sendMemberInvitation']);
    Route::post('/approve-member-invitation', [UserController::class, 'approveMemberInvitation']);

    Route::get('/notifications', [UserController::class, 'getNotifications']);
    Route::get('/check-notification', [UserController::class, 'checkNotification']);

    Route::post('/servers/{server}/register-identity', [ServerController::class, 'registerIdentity']);
    Route::put('/servers/{server}/auth-identity', [ServerController::class, 'authIdentity']);
    Route::resource('servers', ServerController::class, [
        'only' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
    ]);

    Route::post('upload', [FileController::class, 'upload']);
});
