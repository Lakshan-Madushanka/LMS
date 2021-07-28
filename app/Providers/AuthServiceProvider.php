<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\User;
use App\Policies\StudentPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies
        = [
            Student::class => StudentPolicy::class
            // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->defineGates();

        // passport services
        Passport::routes();
        Passport::hashClientSecrets();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

    }

    public function defineGates()
    {
        Gate::define('isAdmin', function (User $user) {
            return User::checkRoles($user, [1,2]);
            // check if auth user is a admin, lecturer, or super_admin
           /* return count(array_intersect([1, 2, 3],
                    $user->roles->pluck('pivot.role_id')->toArray())) > 0;*/
           /* return $user->whereHas('roles', function (Builder $query) {
                $query->whereIn('name', ['lecturer', 'super_admin', 'admin']);
            })->exists();*/
        });
        Gate::define('isManagement', function (User $user) {
            return User::checkRoles($user, [1,2,3]);
            // check if auth user is a admin, lecturer, or super_admin
            /* return count(array_intersect([1, 2, 3],
                     $user->roles->pluck('pivot.role_id')->toArray())) > 0;*/
            /* return $user->whereHas('roles', function (Builder $query) {
                 $query->whereIn('name', ['lecturer', 'super_admin', 'admin']);
             })->exists();*/
        });
    }

}
