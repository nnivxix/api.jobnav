<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ApplyJobController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PersonalApplyJobController;
use App\Http\Controllers\Api\PersonalJobController;
use App\Http\Controllers\Api\RegisterUserController;
use App\Http\Controllers\Api\PersonalCompanyController;
use App\Http\Controllers\Api\PersonalJobUserController;


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
    Route::get('/users', [AuthController::class, 'index'])->name('user.current');
    Route::put('/users', [AuthController::class, 'update'])->name('user.update');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('user.logout');

    Route::get('/personal-companies', [PersonalCompanyController::class, 'index'])->name('personal-company.index');
    Route::post('/personal-companies', [PersonalCompanyController::class, 'store'])->name('personal-company.store');
    Route::get('/personal-companies/{company}', [PersonalCompanyController::class, 'show'])->name('personal-company.show');
    Route::put('/personal-companies/{company}', [PersonalCompanyController::class, 'update'])->name('personal-company.update');
    Route::delete('/personal-companies/{company}', [PersonalCompanyController::class, 'destroy'])->name('personal-company.destroy');

    Route::post('/jobs/{job}/apply', [ApplyJobController::class, 'store'])->name('apply-job.store');

    Route::get('/personal-jobs', [PersonalJobController::class, 'index'])->name('personal-job.index');
    Route::post('/personal-jobs', [PersonalJobController::class, 'store'])->name('personal-job.store');
    Route::put('/personal-jobs/{uuid}', [PersonalJobController::class, 'update'])->name('personal-job.update');
    Route::get('/personal-jobs/{job}', [PersonalJobController::class, 'show'])->name('personal-job.show');
    Route::delete('/personal-jobs/{job}', [PersonalJobController::class, 'destroy'])->name('personal-job.destroy');

    Route::get('/personal-jobs/{job}/user', [PersonalJobUserController::class, 'show'])->name('personal-job-user.show');
    Route::put('/personal-jobs/{job}/user', [PersonalJobUserController::class, 'update'])->name('personal-job-user.update');

    Route::get('/personal-apply-jobs', [PersonalApplyJobController::class, 'index'])->name('personal-apply-job.index');
    Route::get('/personal-apply-jobs/{applyJob}', [PersonalApplyJobController::class, 'show'])->name('personal-apply-job.show');
});

Route::middleware('unauthenticate')->group(function () {
    Route::post('/login', [LoginController::class, 'store'])->name('user.login');
    Route::post('/register', [RegisterUserController::class, 'store'])->name('user.register');
    Route::get('/verify/{user}', [RegisterUserController::class, 'verify'])->name('register-user.verify');
});


Route::get('/jobs', [JobController::class, 'index'])->name('job.index');
Route::get('/jobs/{uuid}', [JobController::class, 'show'])->name('job.show');

// List company

Route::get('/users/{user}', [UserController::class, 'show']);
