<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClipCallbackController;
use App\Http\Controllers\Api\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/clip/callback', [ClipCallbackController::class, 'store']);

Route::middleware('auth:sanctum')->prefix('chat')->group(function () {
    Route::get('/users', [ChatController::class, 'users']);
    Route::get('/messages/{user}', [ChatController::class, 'messages']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::get('/unread', [ChatController::class, 'unread']);
    Route::post('/save-fcm-token', [ChatController::class, 'saveFcmToken']);
});
