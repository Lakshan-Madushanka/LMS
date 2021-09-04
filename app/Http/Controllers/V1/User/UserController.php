<?php

namespace App\Http\Controllers\V1\User;

use App\Helpers\PassportHelper;
use App\Helpers\RequestHelper;
use App\Http\Controllers\V1\ApiController;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Repository\UserRepositoryInterface;
use App\Services\FileService\FileServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends ApiController
{
    private $localFileService;
    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        FileServiceInterface $localFileService
    ) {
        $this->middleware('auth:api');

        $this->userRepository   = $userRepository;
        $this->localFileService = $localFileService;
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

        $validatedData = $request->validated();

        $sanitizedData = $this->userRepository
            ->prepareDataForCreate($validatedData, $this->localFileService);

        $user = $this->userRepository->create($sanitizedData);

        if ($user) {
            $this->addRoles($request, $user);

            if ($user['email_verified_at'] === null) {
                //event(new Registered($user));
            }
        }

        return $this->showOne(Response::$statusTexts[Response::HTTP_CREATED],
            __('messages.created'), null,
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
                $user) and Gate::denies('administrative')) {
            throw new AuthorizationException();
        }

        $validatedData = $request->validated();

        $user = $this->userRepository
            ->prepareUserForUpdate($user, $validatedData,
                $this->localFileService);

        if (RequestHelper::isContainRoles() && $user->isClean()) {
            $this->addRoles($request, $user);

            return $this->showOne(Response::$statusTexts[Response::HTTP_CREATED],
                __('messages.updated'), null, Response::HTTP_CREATED);
        }

        $user = $this->userRepository->update($user);

        $this->addRoles($request, $user);

        if ($user->wasChanged('email') && $user['email_verified_at'] === null) {
            PassportHelper::revokeToken();
        }

        return $this->showOne(Response::$statusTexts[Response::HTTP_CREATED],
            __('messages.updated'), null, Response::HTTP_CREATED);
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
        $policyResponce = Gate::inspect('delete', $user);

        throw_if($policyResponce->denied(),
            new AuthorizationException($policyResponce ?? $policyResponce));

        $this->userRepository->softDelete($user);

        return $this->showOne(Response::$statusTexts[Response::HTTP_OK],
            __('messages.deleted'),
            null, Response::HTTP_OK);
    }

    public function fullTextSearch(Request $request)
    {
       $results = User::search($request->search)->get();

        return $this->showOne(Response::$statusTexts[Response::HTTP_ACCEPTED],
            __('messages.success'),
            $results, Response::HTTP_ACCEPTED);
    }

    public function addRoles(Request $request, User $user)
    {
        if (is_array($request->roles)) {
            if (count(array_intersect([
                Role::names['super_admin'],
                Role::names['admin'],
            ], $request->roles))) {

                throw_unless(Gate::allows('superAdmin'),
                    new AuthorizationException());

                $user->roles()->sync($request->roles);
            }
        } elseif (Gate::allows('administrative')) {
            $user->roles()->syncWithoutDetaching($request->roles);
        } elseif (Gate::allows('lecturer')) {
            $this->authorize('update');

            $user->roles()->sync([Role::names['lecturer']]);
        } else {
            $user->roles()->sync([Role::names['student']]);
        }
    }
}
