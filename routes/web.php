<?php

use App\Http\Controllers\MailTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MailTestController::class, 'index']);
Route::post('/send', [MailTestController::class, 'send'])->name('send');
Route::post('/settings', [MailTestController::class, 'saveSettings'])->name('settings.save');
