<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\HttpFoundation\Response;

class StudentPolicy
{
    use HandlesAuthorization;

    public function show(User $user, $student)
    {
        return User::checkRoles($user, [1, 2, 3]) || $user->id === $student->id;
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {

        return User::checkRoles($user, [1, 2, 3]) || $user->id === $user->id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Student  $student
     *
     * @return mixed
     */
    public function view(User $user, Student $student)
    {

        return User::isAdministrative($user) or $user->id === $student->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Student  $student
     *
     * @return mixed
     */
    public function update(User $user, Student $student)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Student  $student
     *
     * @return mixed
     */
    public function delete(User $user, Student $student)
    {
        echo 'here';
        dd('lak');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Student  $student
     *
     * @return mixed
     */
    public function restore(User $user, Student $student)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Student  $student
     *
     * @return mixed
     */
    public function forceDelete(User $user, Student $student)
    {
        //
    }

}
