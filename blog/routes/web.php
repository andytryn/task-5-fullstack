<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');
Route::get('/cat/{category}/', [WelcomeController::class, 'cat'])->name('welcome.category');
Route::get('/read/{post}/', [WelcomeController::class, 'read'])->name('welcome.read');


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('post', PostController::class);
Route::resource('category', CategoryController::class);
