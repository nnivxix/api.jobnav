<?php

use App\Http\Controllers\ApplyJobController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobController;
use App\Models\Job;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  $features = [
    [
      "icon" => "images/cv.svg",
      "title" => "Resume Builder"
    ],
    [
      "icon" => "images/conversation.svg",
      "title" => "Interview Tips"
    ],
    [
      "icon" => "images/statistic.svg",
      "title" => "Career Advice"
    ],
  ];
  $latest_jobs = Job::query()
    ->inRandomOrder()
    ->with('company')
    ->limit(6)
    ->get();
  $latest_posts = [
    [
      "image" => "images/post_images/1.jpg",
      "title" => "16 Examples of Self-Description in a resume That Can Get Recruiters' Attention",
      "posted_at" => "12/7/2020",
      "excerpt" => "our resume is your first impression to potential employers, so it's important to make it count. In this post, we'll outline 10 common mistakes people make when writing their resumes and how to avoid them."
    ],
    [
      "image" => "images/post_images/2.jpg",
      "title" => "16 Examples of Self-Description in a resume That Can Get Recruiters' Attention",
      "posted_at" => "12/7/2020",
      "excerpt" => "our resume is your first impression to potential employers, so it's important to make it count. In this post, we'll outline 10 common mistakes people make when writing their resumes and how to avoid them."
    ],
    [
      "image" => "images/post_images/3.jpg",
      "title" => "16 Examples of Self-Description in a resume That Can Get Recruiters' Attention",
      "posted_at" => "12/7/2020",
      "excerpt" => "our resume is your first impression to potential employers, so it's important to make it count. In this post, we'll outline 10 common mistakes people make when writing their resumes and how to avoid them."
    ],
    [
      "image" => "images/post_images/4.jpg",
      "title" => "16 Examples of Self-Description in a resume That Can Get Recruiters' Attention",
      "posted_at" => "12/7/2020",
      "excerpt" => "our resume is your first impression to potential employers, so it's important to make it count. In this post, we'll outline 10 common mistakes people make when writing their resumes and how to avoid them."
    ],
    [
      "image" => "images/post_images/5.jpg",
      "title" => "16 Examples of Self-Description in a resume That Can Get Recruiters' Attention",
      "posted_at" => "12/7/2020",
      "excerpt" => "our resume is your first impression to potential employers, so it's important to make it count. In this post, we'll outline 10 common mistakes people make when writing their resumes and how to avoid them."
    ],
    [
      "image" => "images/post_images/6.jpg",
      "title" => "16 Examples of Self-Description in a resume That Can Get Recruiters' Attention",
      "posted_at" => "12/7/2020",
      "excerpt" => "our resume is your first impression to potential employers, so it's important to make it count. In this post, we'll outline 10 common mistakes people make when writing their resumes and how to avoid them."
    ],

  ];
  return view('home', [
    "text" => "We also offer a range of resources to help you succed in your job search and with our personalized job alerts, you can receive notifications when new job opportunities that match your preferences become available.",
    "features" => $features,
    "latest_jobs" => $latest_jobs,
    "latest_posts" => $latest_posts,
  ]);
});
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store']);

Route::controller(UserController::class)->group(function () {
  Route::get('/user',  'index')->middleware(['auth']);
  Route::get('/user/{user:username}', 'show');
  Route::get('/user/{user}/edit', 'edit')->middleware('auth');
  Route::put('/user/update', 'update')->middleware('auth')->name('user.update');
  Route::get('/logout', 'destroy')->middleware('auth');
  Route::get('/register', 'create')->name('register')->middleware('guest');
  Route::post('/register', 'store')->name('save.user');
});

Route::controller(CompanyController::class)->group(function () {
  Route::get('/companies', 'index')->name('companies');
  Route::get('/companies/create', 'create')->name('companies.create')->middleware('auth');
  Route::post('/companies', 'store')->name('companies.store');
  Route::get('/companies/{company:slug}', 'show');
  Route::get('/companies/{company:slug}/edit', 'edit')->middleware('owner.company')->name('companies.edit');
  Route::put('/companies/update/{slug}', 'update')->name('companies.update');
});

Route::controller(JobController::class)->group(function () {
  Route::get('/jobs', 'index')->name('jobs.index');
  Route::get('/jobs/create', 'create')->name('jobs.create')->middleware(['auth', 'has.company']);
  Route::post('/jobs', 'store')->name('jobs.store')->middleware('auth');
  Route::get('/jobs/{job:uuid}', 'show')->name('jobs.show');
  Route::get('/jobs/{job:uuid}/edit', 'edit')->name('jobs.edit');
  Route::put('/jobs/update/{job:uuid}', 'update')->name('jobs.update');
});

Route::controller(ApplyJobController::class)->group(function () {
  Route::get('/jobs/{job:uuid}/apply', 'create')->name('apply.add');
  Route::post('/jobs/apply', 'store')->name('apply.store');
});
