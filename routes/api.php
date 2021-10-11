<?php

use App\Http\Controllers\Api\v1\TransactionController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth.basic', 'as' => 'api.'], function () {
    Route::get('users/{user?}', [UserController::class, 'show'])->name('users.show');

    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
});
