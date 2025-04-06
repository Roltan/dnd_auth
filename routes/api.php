<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/check', [UserController::class, 'check']);

Route::group(['prefix' => '/password'], function () {
    Route::post('/forgot', [PasswordController::class, 'forgot']);
    Route::post('/viewReset', [PasswordController::class, 'viewReset']);
    Route::post('/change', [PasswordController::class, 'change']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/info', [UserController::class, 'info']);
});
