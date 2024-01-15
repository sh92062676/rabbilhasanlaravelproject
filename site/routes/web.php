<?php

use App\Http\Controllers\BlogsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TermsController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'HomeIndex']);
Route::post('/message', [HomeController::class, 'MessageIndex']);


// Pages

Route::get('/Blogs', [BlogsController::class, 'BlogsIndex']);
Route::get('/Courses', [CoursesController::class, 'CoursesIndex']);
Route::get('/Projects', [ProjectsController::class, 'ProjectsIndex']);
Route::get('/Policy', [PolicyController::class, 'PolicyIndex']);
Route::get('/Terms', [TermsController::class, 'TermsIndex']);
Route::get('/Contact', [ContactController::class, 'ContactIndex']);