<?php


namespace App\Repository;


use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    public function find($id): Model;
    public function create(array $data) : Model;
    public function update(Model $model) : Model;
    public function permanentDelete(Model $model);
    public function permanentDeleteMany(Model $model);

}