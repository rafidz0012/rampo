<?php

use App\Http\Controllers\Api\ClipCallbackController;
use Illuminate\Support\Facades\Route;

Route::post('/clip/callback', [ClipCallbackController::class, 'store']);
