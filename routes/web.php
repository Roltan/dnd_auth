<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/info', [UserController::class, 'info']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

Route::post('/refresh', [UserController::class, 'refresh']);
Route::post('/forgot', [UserController::class, 'forgot']);
Route::get('/viewResetPassword', [UserController::class, 'viewResetPassword']);
Route::post('/changePassword', [UserController::class, 'changePassword']);
Route::post('/edit', [UserController::class, 'edit']);
