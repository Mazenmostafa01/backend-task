<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\ForgetPasswordController;


Route::post('/login', [UserController::class, 'store']);
Route::post('/refresh', [UserController::class, 'refresh']);
//password reset
Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLinkMail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');
//categories CRUD
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{categories}', [CategoryController::class, 'show']);
Route::post('/categories/{categories}', [CategoryController::class, 'update']);
Route::delete('/categories/{categories}', [CategoryController::class, 'destroy']);
//products CRUD
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::post('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);