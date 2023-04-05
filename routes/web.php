<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\ImportProductController;
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

//home
Route::get('/', function () {
    return view('home');
});

//student
Route::get('/students', [StudentController::class, 'index'])->name('student.index');
Route::get('/student/{student}', [StudentController::class, 'show'])->name('student.show');
Route::delete('/student/{student}', [StudentController::class, 'destroy'])->name('student.destroy');
Route::get('/student/{student}/edit', [StudentController::class, 'edit'])->name('student.edit');
Route::put('/student/{student}/edit', [StudentController::class, 'update'])->name('student.update');
Route::get('/student-add', [StudentController::class, 'create'])->name('student.create');
Route::post('/student-add', [StudentController::class, 'store'])->name('student.store');

//auth
Route::get('/login', [CustomAuthController::class, 'index'])->name('auth.index');
Route::post('/login', [CustomAuthController::class, 'authentication'])->name('auth.authentication');
Route::get('/register', [CustomAuthController::class, 'create'])->name('auth.create');
Route::post('/register', [CustomAuthController::class, 'store'])->name('auth.store');
Route::get('/dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboard');
Route::get('/logout', [CustomAuthController::class, 'logout'])->name('auth.logout');

//blog
Route::get('/forum', [BlogPostController::class, 'index'])->name('blog.index');
Route::get('/forum/create', [BlogPostController::class, 'create'])->name('blog.create')->middleware('auth');
Route::post('/forum/create', [BlogPostController::class, 'store'])->name('blog.store')->middleware('auth');
Route::get('/forum/{blogPost}', [BlogPostController::class, 'show'])->name('blog.show')->middleware('auth');
Route::get('/forum/{blogPost}/edit', [BlogPostController::class, 'edit'])->name('blog.edit')->middleware('auth');
Route::put('/forum/{blogPost}/edit', [BlogPostController::class, 'update'])->name('blog.update')->middleware('auth');
Route::delete('/forum/{blogPost}', [BlogPostController::class, 'destroy'])->name('blog.destroy')->middleware('auth');
Route::get('/posts/{user}', [BlogPostController::class, 'showuser'])->name('blog.showuser')->middleware('auth');

//user
Route::get('/users', [UserController::class, 'index'])->name('user.index')->middleware('auth');
Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show')->middleware('auth');
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('auth');
Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('auth');
Route::put('/user/{user}/edit', [UserController::class, 'update'])->name('user.update')->middleware('auth');

//file upload (filepond)
Route::post('document/uploads/process', [FileUploadController::class, 'process'])->name('uploads.process')->middleware('auth');
Route::post('products/import', ImportProductController::class)->name('products.import')->middleware('auth');
Route::get('document/upload', [FileUploadController::class, 'create'])->name('document.create')->middleware('auth');
Route::get('documents', [FileUploadController::class, 'index'])->name('document.index')->middleware('auth');
Route::delete('documents', [FileUploadController::class, 'destroy'])->name('document.destroy')->middleware('auth');
Route::get('documents/{user}', [FileUploadController::class, 'userindex'])->name('document.userindex')->middleware('auth');
Route::delete('documents/{user}', [FileUploadController::class, 'destroy'])->middleware('auth');
Route::get('document/{document}', [FileUploadController::class, 'show'])->name('document.show')->middleware('auth');
Route::delete('document/{document}', [FileUploadController::class, 'destroy'])->middleware('auth');

//temp password
Route::get('forgot-password', [CustomAuthController::class, 'forgotPassword'])->name('forgot.password');
Route::post('forgot-password', [CustomAuthController::class, 'tempPassword'])->name
('temp.password');
Route::get('new-password/{user}/{tempPassword}', [CustomAuthController::class,
'newPassword']);
Route::post('new-password/{user}/{tempPassword}', [CustomAuthController::class,
'storeNewPassword']);

//change password (signed in)
Route::get('change-password/{user}', [CustomAuthController::class, 'changePassword'])->name('change.password');
Route::post('change-password/{user}', [CustomAuthController::class, 'storePassword'])->name('store.password');

