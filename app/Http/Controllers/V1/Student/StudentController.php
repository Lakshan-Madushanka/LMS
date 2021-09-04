<?php

namespace App\Http\Controllers\V1\Student;

use App\Http\Controllers\V1\ApiController;
use App\Http\Requests\User\UserCreateRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('store');
        // $this->authorizeResource(Student::class, 'student');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        Gate::authorize('isManagement');

        $allStudents = Cache::remember('allStudents', 1*60*60, function () {
            return collect(Student::lazy());
        });

        return $this->showAll('Ok', 'Query Successed !', $allStudents,
            Response::HTTP_OK);
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student, Request $request)
    {
         $this->authorize('view', $student);
         return $this->showOne('Ok', 'Query Successed', $student,
             Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }

    public function History()
    {
        $studentsLastUpdatedAt = Student::select('updated_at')->orderByDesc('id')
            ->value('updated_at');

        $lastCreatedstudentId = Student::latest()->id;

        return [
            'studentsLastUpdatedAt' => $studentsLastUpdatedAt,
            'lastCreatedstudentId' => $$lastCreatedstudentId,
        ];
    }
}




