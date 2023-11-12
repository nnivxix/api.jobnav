<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ApplyJobController;
use App\Http\Controllers\Api\CompanyController;
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

Auth::loginUsingId(5);
Route::middleware('auth:sanctum',)->group(function () {
    Route::get('/users', [AuthController::class, 'index'])->name('api.user.current');
    Route::put('/users', [AuthController::class, 'update'])->name('api.user.update');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('api.user.logout');

    Route::get('/personal-companies', [PersonalCompanyController::class, 'index'])->name('api.personal-company.index');
    Route::post('/personal-companies', [PersonalCompanyController::class, 'store'])->name('api.personal-company.store');
    Route::get('/personal-companies/{company}', [PersonalCompanyController::class, 'show'])->name('api.personal-company.show');
    Route::put('/personal-companies/{company}', [PersonalCompanyController::class, 'update'])->name('api.personal-company.update');
    Route::delete('/personal-companies/{company}', [PersonalCompanyController::class, 'destroy'])->name('api.personal-company.destroy');

    Route::post('/jobs/{job}/apply', [ApplyJobController::class, 'store'])->name('api.apply-job.store');

    Route::get('/personal-jobs', [PersonalJobController::class, 'index'])->name('api.personal-job.index');
    Route::post('/personal-jobs', [PersonalJobController::class, 'store'])->name('api.personal-job.store');
    Route::put('/personal-jobs/{job}', [PersonalJobController::class, 'update'])->name('api.personal-job.update');
    Route::get('/personal-jobs/{job}', [PersonalJobController::class, 'show'])->name('api.personal-job.show');
    Route::delete('/personal-jobs/{job}', [PersonalJobController::class, 'destroy'])->name('api.personal-job.destroy');

    Route::get('/personal-jobs/{job}/user', [PersonalJobUserController::class, 'show'])->name('api.personal-job-user.show');
    Route::put('/personal-jobs/{job}/user', [PersonalJobUserController::class, 'update'])->name('api.personal-job-user.update');

    Route::get('/personal-apply-jobs', [PersonalApplyJobController::class, 'index'])->name('api.personal-apply-job.index');
    Route::get('/personal-apply-jobs/{applyJob}', [PersonalApplyJobController::class, 'show'])->name('api.personal-apply-job.show');
});

Route::middleware('unauthenticate')->group(function () {
    Route::post('/login', [LoginController::class, 'store'])->name('api.user.login');
    Route::post('/register', [RegisterUserController::class, 'store'])->name('api.user.register');
    Route::get('/verify/{user}', [RegisterUserController::class, 'verify'])->name('api.register-user.verify');
});


Route::get('/jobs', [JobController::class, 'index'])->name('api.job.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('api.job.show');

// List company
Route::get('/companies', [CompanyController::class, 'index'])->name('api.company.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('api.company.show');

Route::get('/users/{user}', [UserController::class, 'show']);
