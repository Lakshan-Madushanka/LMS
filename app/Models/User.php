<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable
        = [
            'email',
            'password',
            'user_id',
            'full_name',
            'n_i_c',
            'full_name',
            'address',
            'nearest_town',
            'gender',
            'contact_no',
            'email',
            'description',
            'image',
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
        ];

    //check roles
    public static function isLecturer(User $user)
    {
        return in_array(3, $user->roles->pluck('id')->toArray());
    }

    public function isAdministrative(User $user)
    {
        return count(array_intersect([1, 2],
                $user->roles->pluck('id')->toArray())) > 0;
    }

    public function isStudent(User $user)
    {
        return in_array(4, $user->roles->pluck('id')->toArray());
    }

    public static function checkRoles($user, array $roles)
    {
        return count(array_intersect($roles,
                $user->roles->pluck('pivot.role_id')->toArray())) > 0;
    }

    // relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id',
            'role_id')->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_course', 'user_id',
            'course_id'

        )->withPivot('course_in_charge')->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'user_subject',
            'user_id', 'subject_id'
        )->withTimestamps();
    }
}
