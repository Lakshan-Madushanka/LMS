<?php

namespace App\Http\Controllers\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\ApiController;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\utills\Messages;
use App\Http\utills\Paths;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $data = $request->input();

        //set password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        }
        //set user_id
        $data['user_id'] = $this->generateUserId();

        //store user image
        if ($this->checkRequestHasFile($request, 'image')) {
            $imageName = $this->setUploadedUserImageName($request);

            //store image
            $image = $request->file('image')
                ->storeAs('/img/users', $imageName, 'public');

            $data['image'] = asset('storage/'.$image); // set db image column
        }

        $user = Student::create($data);

        $this->addRoles($request, $user);

        if(User::isAdministrative($request->user())) {
            $this->addEmailVerifiedColumn($request, $data);
        }else{
            event(new Registered($user));
        }

        return $this->showOne('Created', 'Record created', null,
            Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {

        if ($request->user()->cannot('update',
                $user) and !User::isAdministrative($user)) {

            throw new AuthorizationException();
        }

        if ($this->checkRequestHasFile($request, 'image')) {
            Storage::disk('public')
                ->delete(str_replace(Paths::getPublicStoragePath(), '',
                    $user->image));
        }

        $user->fill($request->except('image', 'email', 'email_verified_at'));

        $this->addEmailVerifiedColumn($request, $user);

        if ($this->isContainRoles($request) && $user->isClean()) {
            $this->addRoles($request, $user);

            return $this->showOne(Response::$statusTexts[Response::HTTP_CREATED],
                'User role Updated', Response::HTTP_CREATED);
        }

        throw_unless($user->isDirty(),
            ValidationException::withMessages(['message' => Messages::isDirty]));

        $user->save();

        $this->addRoles($request, $user);

        return $this->showOne(Response::$statusTexts[Response::HTTP_CREATED],
            'Record Updated', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne(Response::$statusTexts[Response::HTTP_ACCEPTED],
            'User record deleted',
            null, Response::HTTP_ACCEPTED);
    }


    public function isContainRoles(Request $request)
    {
        return $request->has('roles') && !empty($request->roles);
    }

    public function addEmailVerifiedColumn(Request $request, &$data)
    {
        if ($request->only(('email_verified_at')) === 'verified') {
            Gate::authorize('isAdmin');

            $data['email_verified_at'] = now();
        }
    }

    public function addRoles(Request $request, User $user)
    {

        if (is_array($request->roles)) {
            if (count(array_intersect([1, 2], $request->roles))) {
                throw_unless(User::isSuperAdmin($request->user()),
                    new AuthorizationException());
					
                $user->roles()->sync($request->roles);
            }
        } elseif (Gate::allows('isAdmin')) {
            $user->roles()->sync($request->roles);
        } elseif (User::isLecturer($user)) {
            $this->authorize('update');
			
            $user->roles()->sync([3]);
        } else {
            $user->roles()->sync([4]);
        }
    }

    public function setUploadedUserImageName(Request $request)
    {
        $lastInsertedId = $this->lastInsertedUserId();

        $imageId = $lastInsertedId + 1;//set image id to current id
		
		// convert filenames to valid url format
        $originaleImageName = preg_replace('![^a-z0-9]+!i', '-',
            $request->file('image')->getClientOriginalName());

        $imageExtesion = $request->file('image')
            ->getClientOriginalExtension();
			
        $fileName = pathinfo($originaleImageName, PATHINFO_FILENAME);
        $imageName = "{$fileName}-{$imageId}.{$imageExtesion}";

        return $imageName;
    }

}
