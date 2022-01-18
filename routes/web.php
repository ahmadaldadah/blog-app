<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTagController;

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
Route::get('/','\App\Http\Controllers\HomeController@home')->name('home');
Route::get('/contact','\App\Http\Controllers\HomeController@contact')->name('contact');
Route::get('/secret','\App\Http\Controllers\HomeController@secret')
    ->name('secret')
    ->middleware('can:home.secret');
Route::resource('posts',PostController::class);
Route::get('/posts/tag/{tag}',[PostTagController::class,'index'])->name('posts.tags.index');
Route::resource('posts.comment','App\Http\Controllers\PostCommentController')->only(['store']);
Route::resource('users.comments','App\Http\Controllers\UserCommentController')->only(['store']);
Route::resource('users','App\Http\Controllers\UserController')->only(['show','edit','update']);
Auth::routes();
