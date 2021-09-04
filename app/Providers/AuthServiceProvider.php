<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Policies\StudentPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;
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


        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->min(6)->letters()->mixedCase()->numbers()->symbols()->uncompromised()
                : $rule;
        });


        $this->registerPolicies();
        $this->defineGates();

        // passport services
        Passport::routes(null, ['prefix' => 'api/V1/oauth']);
        //Passport::hashClientSecrets();
        Passport::tokensExpireIn(now()->addDays(1500));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

    }

    public function defineGates()
    {
        Gate::define('superAdmin', function (User $user) {

            return in_array(Role::names['super_admin'],
                $user->roles->pluck('id')->toArray());
        });

        Gate::define('administrative', function (User $user) {

            return count(array_intersect([
                    Role::names['super_admin'], Role::names['admin'],
                ],
                    $user->roles->pluck('id')->toArray())) > 0;
        });

        Gate::define('lecturer', function (User $user) {
            return in_array(Role::names['lecturer'],
                $user->roles->pluck('id')->toArray());
        });

        Gate::define('student', function (User $user) {
            return in_array(Role::names['student'],
                $user->roles->pluck('id')->toArray());
        });
    }

}
