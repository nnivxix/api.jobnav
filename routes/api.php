<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ApplyJobController;
use App\Http\Controllers\Api\RegisterUserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [AuthController::class, 'store']);
Route::post('/register', [RegisterUserController::class, 'store']);

Route::get('/users/{user}', [UserController::class, 'show']);

Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{job}', [JobController::class, 'show']);

Route::middleware('auth:sanctum',)->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::get('/me', [AuthController::class, 'index']);

    Route::post('/jobs/{job}/apply', [ApplyJobController::class, 'store']);

    Route::put('/profile', [ProfileController::class, 'update']);
});
