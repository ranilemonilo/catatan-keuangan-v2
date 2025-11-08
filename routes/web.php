<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::group(['prefix' => 'app', 'middleware' => 'check.auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('app.home');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('app.transactions');
    Route::get('/transactions/{transaction_id}', [TransactionController::class, 'detail'])->name('app.transactions.detail');
    Route::get('/statistics', [TransactionController::class, 'statistics'])->name('app.statistics'); // Tambahkan ini
});

Route::get('/', function () {
    return redirect()->route('app.home');
});