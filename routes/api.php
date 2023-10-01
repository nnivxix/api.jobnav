<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ApplyJobController;
use App\Http\Controllers\Api\PersonalCompanyController;
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
    Route::get('/personal-jobs', [PersonalJobController::class, 'index']);
    Route::post('/personal-jobs', [PersonalJobController::class, 'store']);
    Route::put('/personal-jobs/{uuid}', [PersonalJobController::class, 'update']);
    Route::get('/personal-jobs/{job}', [PersonalJobController::class, 'show']);
    Route::delete('/personal-jobs/{job}', [PersonalJobController::class, 'destroy']);

    Route::get('/personal-companies', [PersonalCompanyController::class, 'index']);
    Route::post('/personal-companies', [PersonalCompanyController::class, 'store']);
    Route::get('/personal-companies/{company}', [PersonalCompanyController::class, 'show']);
    Route::put('/personal-companies/{company}', [PersonalCompanyController::class, 'update']);
    Route::delete('/personal-companies/{company}', [PersonalCompanyController::class, 'destroy']);

    Route::get('/users', [AuthController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::put('/users', [AuthController::class, 'update']);


    Route::post('/jobs/{job}/apply', [ApplyJobController::class, 'store']);
});

Route::post('/login', [AuthController::class, 'store']);

Route::post('/register', [RegisterUserController::class, 'store']);
Route::get('/verify/{user}', [RegisterUserController::class, 'verify'])->name('register-user.verify');


Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{job}', [JobController::class, 'show']);

Route::get('/users/{user}', [UserController::class, 'show']);
