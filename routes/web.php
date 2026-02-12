<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\N8nController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ClipperController;
use App\Http\Controllers\Server\ServerMonitorController;
use App\Http\Controllers\NontonController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Keuangan
    Route::resource('incomes', IncomeController::class)->except(['show']);
    Route::resource('expenses', ExpenseController::class)->except(['show']);
    Route::resource('subscriptions', SubscriptionController::class)->except(['show']);

    // Catatan
    Route::resource('notes', NoteController::class);

    // To-do
    Route::resource('todos', TodoController::class)->except(['show']);
    Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');

    // Dokumen
    Route::resource('documents', DocumentController::class)->except(['edit', 'update']);
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // N8n Clipper
    Route::get('/n8n', [N8nController::class, 'index'])->name('n8n.index');
    Route::post('/n8n', [N8nController::class, 'send'])->name('n8n.send');

    // Server Monitor
    Route::get('/monitor', [ServerMonitorController::class, 'index'])->name('monitor.index');
    Route::get('/monitor/stats', [ServerMonitorController::class, 'stats'])->name('monitor.stats');

    // Nonton
    Route::prefix('nonton')->name('nonton.')->group(function () {
        Route::get('/', [NontonController::class, 'index'])->name('index');
        Route::get('/search', [NontonController::class, 'search'])->name('search');
        Route::get('/category/{category}', [NontonController::class, 'category'])->name('category');
        Route::get('/watch/{detailPath}', [NontonController::class, 'watch'])->name('watch');
        Route::get('/watch/{detailPath}', [NontonController::class, 'watch'])->name('watch');
        Route::get('/detail/{detailPath}', [NontonController::class, 'detail'])->name('detail')->where('detailPath', '.*');
    });

    // User Management
    Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show', 'edit', 'update']);

    // Chat
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat-unread', [\App\Http\Controllers\ChatController::class, 'checkUnread'])->name('chat.unread');

    Route::post('/clipper', [ClipperController::class, 'analyze'])->name('clipper.analyze');
    Route::post('/clip/{candidate}', [ClipperController::class, 'process'])->name('clip.process');
    Route::put('/candidates/{candidate}', [ClipperController::class, 'update'])->name('candidates.update');
    Route::get('/clips/{filename}', function ($filename) {
        $path = "/clips/" . $filename;

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'video/mp4'
        ]);
    });

});

require __DIR__ . '/auth.php';

