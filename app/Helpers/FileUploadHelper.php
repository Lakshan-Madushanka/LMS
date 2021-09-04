<?php


namespace App\Helpers;


use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FileUploadHelper
{
    public static function checkFileIsValid(string $fileName)
    {
        if (request()->hasFile($fileName)) {
            if (!request()->file($fileName)->isValid()) {
                throw ValidationException::withMessages(['massage' =>  __('messages.invalidFile')]);
            }

            return true;
        }
    }

    public static function setUploadedFileName($lastInsertedId)
    {
        $imageId = $lastInsertedId + 1;//set image id to current id

        // convert filenames to valid url format
        $originaleImageName = preg_replace('![^a-z0-9]+!i', '-',
            \request()->file('image')->getClientOriginalName());

        $imageExtesion = \request()->file('image')
            ->getClientOriginalExtension();

        $fileName = pathinfo($originaleImageName, PATHINFO_FILENAME);
        $imageName = "{$fileName}-{$imageId}.{$imageExtesion}";

        return $imageName;
    }
}