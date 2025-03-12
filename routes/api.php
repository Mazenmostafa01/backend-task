<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgetPasswordController;

Route::post('/login', [UserController::class, 'store']);
Route::post('/refresh', [UserController::class, 'refresh']);


Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLinkMail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');