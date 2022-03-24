<?php

namespace App\Service;

use App\Interface\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

abstract class BaseService
{
    protected string $class;
    protected RepositoryInterface $repository;

    public function create(array $data): Model
    {
        $resource = $this->fillEntity($data);
        return $this->repository->create($resource);
    }

    public function findAll(int $per_page = null, string $user_id = null): ?LengthAwarePaginator
    {
        return $this->repository->findAll($per_page, $user_id);
    }


    public function find(string $id, string $user_id = null): ?Model
    {
        $resource = $this->repository->find($id, $user_id);

        if (is_null($resource)) {
            throw new NotFoundResourceException('Resource not found.');
        }

        return $resource;
    }

    public function update(string $id, array $data, string $user_id = null): Model
    {
        $resource = $this->find($id, $user_id);
        $data = $this->fillEntity($data);

        return $this->repository->update($resource, $data);
    }

    public function delete(string $id, string $user_id = null): bool
    {
        $resource = $this->find($id, $user_id);
        $this->repository->delete($resource);

        return true;
    }

    public function fillEntity(array $data): Model
    {
        return new $this->class($data);
    }
}
