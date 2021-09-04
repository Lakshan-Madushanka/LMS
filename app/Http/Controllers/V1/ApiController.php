<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    use ApiResponser;

    public function checkRequestHasFile(Request $request, string $fileName)
    {
        if ($request->hasFile($fileName)) {
            if (!$request->file($fileName)->isValid()) {
                throw ValidationException::withMessages(['massage' => 'Invalid file contents']);
            }

            return true;
        }
    }

    public  function generateUserId ()
    {
        $userId = $this->lastInsertedUserId() + 1;
        return Str::random(4).'_'.$userId;

    }

    public function lastInsertedUserId()
    {
        $lastInsertedRecord = User::all()
            ->sortByDesc('id')
            ->values()
            ->first();

        return $lastInsertedRecord->id;
    }

}
