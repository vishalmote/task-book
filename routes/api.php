<?php

use App\Http\Controllers\PAuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
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

Route::post('/register', [PAuthController::class, 'register'])->name('api.register');
Route::post('/login', [PAuthController::class, 'login'])->name('api.login');
Route::middleware('auth:api')->group(function () {
    Route::post('me', [PAuthController::class, 'me'])->name('api.me');
    Route::post('logout', [PAuthController::class, 'logout'])->name('api.logout');
    Route::post('createTask', [TaskController::class, 'createTask'])->name('api.createTask');
    Route::post('listTask', [TaskController::class, 'listTask'])->name('api.listTask');
});
