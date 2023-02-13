<?php

use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleLogin;
use \App\Http\Controllers\GroupingController;
use App\Http\Controllers\LoginController;
use \App\Http\Controllers\GroupController;
use \App\Http\Controllers\IndexController;



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



Route::get('/login', [LoginController::class, 'login_index'])->name('login');
Route::get('auth/google', [GoogleLogin::class, 'loginGoogle'])->name('auth.google');
Route::get('/callback', [GoogleLogin::class, 'callbackFromGoogle'])->name('auth.callback');
Route::get('/logout', [GoogleLogin::class, 'logout'])->name('logout');

Route::get('/',[IndexController::class,'index']);//->name('home')->middleware(['auth']);
Route::get('/members/55015', function(){
    return view('55015');
});
Route::get('/getcourse',[CourseController::class,'getAllCourses']);

Route::get('/cours', function(){
    return view('courses');
});
