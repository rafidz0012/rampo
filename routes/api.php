<?php

use App\Http\Controllers\Api\ClipCallbackController;
use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;

Route::post('/clip/callback', [ClipCallbackController::class, 'store']);

Route::middleware('auth:sanctum')->prefix('chat')->group(function () {
    Route::get('/users', [ChatController::class, 'users']);
    Route::get('/messages/{user}', [ChatController::class, 'messages']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::get('/unread', [ChatController::class, 'unread']);
});
