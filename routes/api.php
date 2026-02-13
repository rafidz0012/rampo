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
Route::get('/test-notif', function () {

    $token = "eX0sqJ5WSLCVQCsOn3IX7Y:APA91bEaNAXsUR6gOC9E42jD0CcZ7h-HjZU_eGTcS83YNgyhXQ57Zu3kxv_tpesHBsjLh9dNk1sZHt9tMRYiQ90ll6DR3pxrHM5ShJLWUQ3ZcjlQuDefob0";

    $response = Http::withHeaders([
        'Authorization' => 'key=AAAAOj9fgDc:APA91bFT4cPM2QqWmYZEWcGK2hW9Yet-xAA4mmoPxn2Y9WMXNBM00FGZwPHnuuXKf6HKvIj1CaBHhajB6qvAL46Zi-ThV0JZZglbMdUhYkPUApJqXmivdIcXo8k50B2O4KLASruD5XFw',
        'Content-Type' => 'application/json'
    ])->post('https://fcm.googleapis.com/fcm/send', [
        "to" => $token,
        "notification" => [
            "title" => "Test Laravel",
            "body" => "Kalau bunyi berarti berhasil 🔔"
        ]
    ]);

    return $response->body();
});

Route::post('/clip/callback', [ClipCallbackController::class, 'store']);

Route::middleware('auth:sanctum')->prefix('chat')->group(function () {
    Route::get('/users', [ChatController::class, 'users']);
    Route::get('/messages/{user}', [ChatController::class, 'messages']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::get('/unread', [ChatController::class, 'unread']);
    Route::post('/save-fcm-token', [ChatController::class, 'saveFcmToken']);
});
