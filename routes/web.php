<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\PlayerController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/question', [QuestionController::class, 'index'])->name('question.index');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Game session routes
    Route::get('/sessions/manage', [GameSessionController::class, 'manage'])->name('sessions.manage'); // List/manage sessions
    Route::get('/sessions/all', [GameSessionController::class, 'all'])->name('sessions.all'); // List all sessions summary
    Route::get('/sessions/manage/{session}', [GameSessionController::class, 'showManage'])->name('sessions.manage.show'); // Details of a session for management

    Route::post('/sessions/create/{quiz}', [GameSessionController::class, 'create'])->name('sessions.create'); // Create a new session
    Route::get('/sessions/{session}', [GameSessionController::class, 'show'])->name('sessions.show'); // Return session info
    Route::get('/sessions/{session}/players', [GameSessionController::class, 'players'])->name('sessions.players'); // Return session players

    // Session management actions
    Route::post('/sessions/{session}/erase-players', [GameSessionController::class, 'erasePlayers'])->name('sessions.erasePlayers');
    Route::post('/sessions/{session}/close', [GameSessionController::class, 'closeSession'])->name('sessions.close'); // Set status to 'playing'
    Route::post('/sessions/{session}/terminate', [GameSessionController::class, 'terminateSession'])->name('sessions.terminate'); // Set status to 'finished'

    // Player join
    Route::get('/join', [PlayerController::class, 'showJoinPage'])->name('player.join');
    Route::post('/join', [PlayerController::class, 'join'])->name('player.join.post');

    // Waiting Room
    Route::get('/player/{player}/wait', [PlayerController::class, 'wait'])->name('player.wait');

});

require __DIR__.'/auth.php';
