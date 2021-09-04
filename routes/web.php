<?php

use App\Http\Controllers\V1\Auth\GoogleLoginController;
use App\Http\Controllers\V1\Student\StudentController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

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
    return view('welcome');
})->name('home');


Route::get('home', function () {
    return view('welcome');
});

//auth routes
Route::get('/google/oauth',
    [GoogleLoginController::class, 'redirect'])->name('googleLoginRedirect');

Route::get('/google/oauth/callback',
    [GoogleLoginController::class, 'register'])->name('googleLoginCallback');
