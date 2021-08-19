<?php

use App\Exceptions\StudentModelNotFoundException;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\v1\Auth\GoogleLoginController;
use App\Http\Controllers\v1\Auth\PasswordResetController;
use App\Http\Controllers\v1\Auth\VerifyEmailController;
use App\Http\Controllers\v1\Student\StudentController;
use App\Http\Controllers\v1\User\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Traits\ApiResponser;
use Symfony\Component\HttpFoundation\Response;

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

//Auth routes
Route::get('/email/verify/{id}/{hash}',
    [VerifyEmailController::class, 'verify'])->name('verification.verify');

Route::get('/email/resend/{id}',
    [VerifyEmailController::class, 'resend'])->name('verification.resend');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//////////////////////////////////social login//////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////
Route::name('password')->group(function () {

    Route::post('/password/forgot',
        [PasswordResetController::class, 'forgot'])->middleware(['throttle:1']);
    Route::get('/password/reset',
        [PasswordResetController::class, 'reset'])->name('.reset');
    Route::post('/password/reset',
        [PasswordResetController::class, 'reset'])->name('.resetPost');

});


Route::apiResource('users', UserController::class)->except([
    'index', 'show',
]);

Route::apiResource('students', StudentController::class);

Route::apiResource('courses', CourseController::class);



