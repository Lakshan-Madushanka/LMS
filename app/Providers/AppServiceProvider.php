<?php

namespace App\Providers;

use App\Exceptions\StudentModelNotFoundException;
use App\Exceptions\UserModelNotFoundException;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::model('student', Student::class, function ($student) {
            throw new StudentModelNotFoundException($student);
        });

        Route::model('user', User::class, function ($user) {
            throw new UserModelNotFoundException($user);
        });
    }
}
