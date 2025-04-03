<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/info', [UserController::class, 'info']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

Route::post('/refresh', [UserController::class, 'refresh']);
Route::post('/forgot', [UserController::class, 'forgot']);
Route::get('/viewResetPassword', [UserController::class, 'viewResetPassword']);
Route::post('/changePassword', [UserController::class, 'changePassword']);
Route::post('/edit', [UserController::class, 'edit']);
