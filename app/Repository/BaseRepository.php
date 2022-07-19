<?php

namespace App\Repository;

use App\Interfaces\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected string $class;

    public function create(Model $entity): Model
    {
        return $this->class::create($entity->getAttributes());
    }

    public function findAll(int $per_page = null, string $user_id = null): ?LengthAwarePaginator
    {
        return is_null($user_id) ? $this->class::paginate($per_page) : $this->findBy('user_id', $user_id, $per_page);
    }

    public function find(string $id, string $user_id = null): ?Model
    {
        return is_null($user_id) ? $this->class::find($id) : $this->class::find($id)?->where('user_id', $user_id)->first();
    }

    public function findBy(string $field, mixed $value, int $per_page = null): ?LengthAwarePaginator
    {
        return $this->class::where($field, $value)->paginate($per_page);
    }

    public function findOneBy(string $field, mixed $value): ?Model
    {
        return $this->class::where($field, $value)->first();
    }

    public function update(Model $entity, Model $data): Model
    {
        $entity->fill($data->getAttributes());
        $entity->save();

        return $entity;
    }

    public function delete(Model $entity): ?bool
    {
        return $entity->delete();
    }
}
