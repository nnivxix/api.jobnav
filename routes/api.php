<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ApplyJobController;
use App\Http\Controllers\Api\PersonalJobController;
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

Route::middleware('auth:sanctum',)->group(function () {
    Route::get('/users/posts', [PersonalJobController::class, 'index']);
    Route::post('/users/posts', [PersonalJobController::class, 'store']);
    Route::put('/users/posts/{uuid}', [PersonalJobController::class, 'update']);

    Route::get('/users', [AuthController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::put('/users', [AuthController::class, 'update']);


    Route::post('/jobs/{job}/apply', [ApplyJobController::class, 'store']);
});

Route::post('/login', [AuthController::class, 'store']);
Route::post('/register', [RegisterUserController::class, 'store']);


Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{job}', [JobController::class, 'show']);

Route::get('/users/{user}', [UserController::class, 'show']);
