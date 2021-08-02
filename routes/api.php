<?php

use App\Exceptions\StudentModelNotFoundException;
use App\Http\Controllers\v1\Student\StudentController;
use App\Http\Controllers\v1\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('users', UserController::class)->except([
    'index', 'show'
]);
Route::apiResource('students', StudentController::class);

Route::apiResource('courses', \App\Http\Controllers\CourseController::class);

