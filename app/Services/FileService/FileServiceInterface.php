<?php


namespace App\Services\FileService;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface FileServiceInterface
{
    public function storeFile(
        string $path,
        string $fileName,
        string $storeAsName,
        string $disk
    );

    public function updateFile(
        string $path,
        string $fileName,
        string $storeAsName,
        string $disk,
        string $prviousFileName = null
    );

    public function deleteFile(string $locaton, string $disk);

    public function setUploadedFileName(int $lastInsertedId);
}