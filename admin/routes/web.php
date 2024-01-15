<?php

use App\Http\Controllers\BlogsController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'HomeIndex'])->middleware('loginCheck');

// visitor 
Route::get('/visitors', [VisitorController::class, 'VisitorIndex'])->middleware('loginCheck');

// services
Route::get('/services', [ServicesController::class, 'ServiceIndex'])->middleware('loginCheck');
Route::get('/getservices', [ServicesController::class, 'getServices'])->middleware('loginCheck');
Route::get('/deleteservice/{id}', [ServicesController::class, 'DeleteService'])->middleware('loginCheck');
Route::get('/editservice/{id}', [ServicesController::class, 'EditService'])->middleware('loginCheck');
Route::post('/updateservice/{id}', [ServicesController::class, 'updateService'])->middleware('loginCheck');
Route::post('/addservice', [ServicesController::class, 'addService'])->middleware('loginCheck');

// courses
Route::get('/courses', [CoursesController::class, 'CoursesIndex'])->middleware('loginCheck');
Route::get('/getcourses', [CoursesController::class, 'getCourses'])->middleware('loginCheck');
Route::post('/addcourse', [CoursesController::class, 'addCource'])->middleware('loginCheck');
Route::get('/deletecourse/{id}', [CoursesController::class, 'DeleteCourse'])->middleware('loginCheck');
Route::get('/editcourse/{id}', [CoursesController::class, 'EditCourse'])->middleware('loginCheck');
Route::post('/updatecourse/{id}', [CoursesController::class, 'updateCourse'])->middleware('loginCheck');


// Projects
Route::get('/projects', [ProjectsController::class, 'ProjectsIndex'])->middleware('loginCheck');
Route::get('/getprojects', [ProjectsController::class, 'getProjects'])->middleware('loginCheck');
Route::post('/addproject', [ProjectsController::class, 'addProject'])->middleware('loginCheck');
Route::get('/deleteproject/{id}', [ProjectsController::class, 'DeleteProject'])->middleware('loginCheck');
Route::get('/editproject/{id}', [ProjectsController::class, 'EditProject'])->middleware('loginCheck');
Route::post('/updateproject/{id}', [ProjectsController::class, 'updateProject'])->middleware('loginCheck');


// Messages
Route::get('/messages', [MessagesController::class, 'MessagesIndex'])->middleware('loginCheck');
Route::get('/getmessages', [MessagesController::class, 'getMessages'])->middleware('loginCheck');
Route::get('/deletemessage/{id}', [MessagesController::class, 'DeleteMessage'])->middleware('loginCheck');

// Blogs
Route::get('/blogs', [BlogsController::class, 'BlogsIndex'])->middleware('loginCheck');
Route::get('/getblogs', [BlogsController::class, 'getBlogs'])->middleware('loginCheck');
Route::post('/addblog', [BlogsController::class, 'addBlogs'])->middleware('loginCheck');
Route::get('/editblog/{id}', [BlogsController::class, 'EditBlogs'])->middleware('loginCheck');
Route::post('/updateblog/{id}', [BlogsController::class, 'UpdateBlogs'])->middleware('loginCheck');
Route::get('/deleteblog/{id}', [BlogsController::class, 'DeleteBlogs'])->middleware('loginCheck');

// Reviews
Route::get('/reviews', [ReviewsController::class, 'ReviewsIndex'])->middleware('loginCheck');
Route::get('/getreviews', [ReviewsController::class, 'getReviews'])->middleware('loginCheck');
Route::post('/addreview', [ReviewsController::class, 'addReviews'])->middleware('loginCheck');
Route::get('/editreview/{id}', [ReviewsController::class, 'EditReviews'])->middleware('loginCheck');
Route::post('/updatereview/{id}', [ReviewsController::class, 'UpdateReviews'])->middleware('loginCheck');
Route::get('/deletereview/{id}', [ReviewsController::class, 'DeleteReviews'])->middleware('loginCheck');


// Login
Route::get('/admin', [LoginController::class, 'Login']);
Route::post('/onLogin', [LoginController::class, 'onLogin']);
Route::get('/onLogout', [LoginController::class, 'Logout']);


// Photo Gallery
Route::get('/gallery', [GalleryController::class, 'GalleryIndex'])->middleware('loginCheck');
Route::post('/photoUpload', [GalleryController::class, 'PhotoUpload'])->middleware('loginCheck');
Route::get('/photojson', [GalleryController::class, 'PhotoJson'])->middleware('loginCheck');
Route::get('/PhotoJsonById/{id}', [GalleryController::class, 'PhotoJsonById'])->middleware('loginCheck');
Route::post('/photoDelete', [GalleryController::class, 'PhotoDelete'])->middleware('loginCheck');
// Home
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
