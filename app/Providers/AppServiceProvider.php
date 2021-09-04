<?php

namespace App\Providers;

use App\Exceptions\StudentModelNotFoundException;
use App\Exceptions\UserModelNotFoundException;
use App\Models\Student;
use App\Models\User;
use App\Repository\Eloquent\UserRepository;
use App\Repository\UserRepositoryInterface;
use App\Services\FileService\FileServiceInterface;
use App\Services\FileService\LocalFileService;
use Illuminate\Database\Eloquent\Model;
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
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // bindings
        $this->app->bind(FileServiceInterface::class, function () {
            return new LocalFileService();
        });

        Route::model('student', Student::class, function ($student) {
            throw new StudentModelNotFoundException($student);
        });

        Route::model('user', User::class, function ($user) {
            throw new UserModelNotFoundException($user);
        });
    }
}
