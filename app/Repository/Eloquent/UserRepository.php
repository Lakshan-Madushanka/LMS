<?php


namespace App\Repository\Eloquent;


use App\Helpers\AuthHelper;
use App\Helpers\FileUploadHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Services\FileService\LocalFileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Repository\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function prepareDataForCreate(
        array $validatedData,
        LocalFileService $fileService
    ): array {
        $data = $validatedData;

        AuthHelper::addEmailVerifiedColumn($data);

        //set password
        //  $data['password'] = Hash::make($data['password']);

        //set user_id
        $data['user_id'] = $this->generateUserId();

        //store user image
        if (array_key_exists('image', $data)) {

            $imageName = FileUploadHelper::setUploadedFileName(
                UserHelper::lastInsertedUserId());

            $image = $fileService->storeFile('img/users/', 'image', $imageName,
                'public');

            $data['image'] = asset('storage/'.$image); // set db image column
        }

        return $data;
    }

    public function prepareUserForUpdate(
        User $user,
        array $data,
        LocalFileService $fileService
    ): User {

        AuthHelper::addEmailVerifiedColumn($data);

        // delete existing file
        $fileService->deleteFile(str_replace(asset('storage'), '',
            $user->image), 'public');

        //store new file
        if (array_key_exists('image', $data)) {

            $imageName     = FileUploadHelper::setUploadedFileName(
                UserHelper::lastInsertedUserId());

            $image         = $fileService->updateFile('img/users/', 'image',
                $imageName, 'public', $user->image);

            $data['image'] = asset('storage/'.$image); // set db image column
        }
        $user = $user->fill($data);

        return $user;
    }

    public function softDelete(User $user)
    {
        $user->delete();
    }

    public function generateUserId()
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

    /*  public function getSoftDeletedUsers(User $user)
      {

      }*/

    public function restore(User $user)
    {
        // TODO: Implement restore() method.
    }
}