<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', function () {
    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::view("/", "chat")->name('chat');

    Route::get("/contacts", [\App\Http\Controllers\ProfileController::class, 'index'])->name('contacts');

    Route::get("/contact/user_id/{user_id}", [\App\Http\Controllers\UserController::class, 'index'])->name('getMessages');

    Route::post('/send-message', [\App\Http\Controllers\MessageController::class, 'sendMessage'])->name('send-message');

    Route::get("/messages/sender_id/{sender_id}/receiver_id/{receiver_id}", [\App\Http\Controllers\MessageController::class, 'index'])->name('messages');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
