<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MinecraftAuthController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/test', [TestController::class, 'index']);
Route::post('/user/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/auth', [AuthController::class, 'auth']);
    Route::post('/minecraft-auth', MinecraftAuthController::class);

    Route::resource('server', ServerController::class, [
        'only' => [
            'index',
            'store',
        ],
    ]);
});
