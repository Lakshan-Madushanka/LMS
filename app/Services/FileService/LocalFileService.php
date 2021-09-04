<?php


namespace App\Services\FileService;


use App\Helpers\FileUploadHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\FileHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocalFileService implements FileServiceInterface
{

    public function storeFile(
        string $path,
        string $fileName,
        string $storeAsName,
        string $disk
    ) {
        if (FileUploadHelper::checkFileIsValid($fileName)) {
            $image = \request()->file($fileName)
                ->storeAs($path, $storeAsName, 'public');

            return $image;
        }
    }

    public function updateFile(
        string $path,
        string $fileName,
        string $storeAsName,
        string $disk,
        string $prviousFileName = null
    ) {

        if (FileUploadHelper::checkFileIsValid($fileName)) {
            if($prviousFileName) {
                $this->deleteFile(str_replace(asset('storage'), '',
                    $prviousFileName));
            }
            $image = \request()->file($fileName)
                ->storeAs($path, $storeAsName, 'public');

            return $image;
        }

    }

    public function deleteFile($location, $disk)
    {
        Storage::disk($disk)->delete($location);

    }

    public function setUploadedFileName($lastInsertedId)
    {

        $imageId = $lastInsertedId + 1;//set image id to current id

        // convert filenames to valid url format
        $originaleImageName = preg_replace('![^a-z0-9]+!i', '-',
            \request()->file('image')->getClientOriginalName());

        $imageExtesion = \request()->file('image')
            ->getClientOriginalExtension();

        $fileName  = pathinfo($originaleImageName, PATHINFO_FILENAME);
        $imageName = "{$fileName}-{$imageId}.{$imageExtesion}";

        return $imageName;
    }
}