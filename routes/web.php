<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ChatController;

Route::get('/check-path', function() {
    return "Путь к папке public: " . public_path() . "<br>" .
           "Файл favicon.png существует: " . (file_exists(public_path('favicon.png')) ? 'ДА' : 'НЕТ') . "<br>" .
           "Путь к файлу: " . public_path('favicon.png');
});


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/my-items', [ItemController::class, 'myItems'])->name('items.my');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::post('/items/{item}/return', [ItemController::class, 'markAsReturned'])->name('items.return');

    Route::get('/matches', [MatchController::class, 'index'])->name('matches');

    Route::get('/my-chats', [ChatController::class, 'index'])->name('chats.index');

    Route::post('/chat/start/{item}', [ItemController::class, 'startChat'])->name('chat.start');
    Route::get('/chat/{match}', [ChatController::class, 'show'])->name('chat');
    Route::post('/chat/{match}', [ChatController::class, 'store']);

    // НОВЫЙ МАРШРУТ ДЛЯ УДАЛЕНИЯ ЧАТА
    Route::delete('/chat/{match}', [ChatController::class, 'destroy'])->name('chat.destroy');
});

Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');
