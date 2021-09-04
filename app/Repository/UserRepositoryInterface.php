<?php


namespace App\Repository;


use App\Helpers\FileUploadHelper;
use App\Models\User;
use App\Services\FileService\LocalFileService;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
      public function softDelete(User $user);
    //public function getSoftDeletedUsers();
    //public function restore(User $user);

    public function prepareUserForUpdate(
        User $user,
        array $data,
        LocalFileService $fileService
    ): User;

    public function prepareDataForCreate(
        array $data,
        LocalFileService $fileService
    ): array;
}