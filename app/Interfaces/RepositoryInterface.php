<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function create(Model $entity): Model;
    public function findAll(int $per_page = null, string $user_id = null): ?LengthAwarePaginator;
    public function find(string $id, string $user_id = null): ?Model;
    public function findBy(string $field, mixed $value, int $per_page = null): ?LengthAwarePaginator;
    public function findOneBy(string $field, mixed $value): ?Model;
    public function update(Model $entity, Model $data): Model;
    public function delete(Model $entity): ?bool;
}
