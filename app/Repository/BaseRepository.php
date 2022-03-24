<?php

namespace App\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected string $class;

    public function create(Model $entity): Model
    {
        return $this->class::create($entity->getAttributes());
    } 

    public function findAll(int $per_page = null): ?LengthAwarePaginator
    {
        return $this->class::paginate($per_page);
    }

    public function find(string $id): ?Model
    {
        return $this->class::find($id);
    }

    public function findBy(string $field, mixed $value): ?Model
    {
        return $this->class::where($field, $value)->first();
    }

    public function update(Model $entity, Model $data): Model
    {
        $entity->fill($data->getAttributes());
        $entity->save();

        return $entity;
    }

    public function delete(string $id): ?bool
    {
        return $this->class::destroy($id);
    }
}
