<?php


namespace App\Repository\Eloquent;


use App\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class BaseRepository implements EloquentRepositoryInterface
{
    protected $model;

    public function __contruct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id): Model
    {
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model): Model
    {
        throw_unless($model->isDirty(),
            ValidationException::withMessages(['message' => __('messages.isDirty')]));

        $model->save();

        return $model->refresh();
    }

    public function permanentDelete(Model $model)
    {
        $model->forceDelete();
    }

    public function permanentDeleteMany(Model $model)
    {
        // TODO: Implement permanentDeleteMany() method.
    }
}