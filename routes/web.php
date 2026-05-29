<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailTestController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('env.auth')->group(function () {
    Route::get('/', [MailTestController::class, 'index']);
    Route::post('/send', [MailTestController::class, 'send'])->name('send');
    Route::post('/settings', [MailTestController::class, 'saveSettings'])->name('settings.save');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
