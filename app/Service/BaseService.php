<?php

namespace App\Service;

use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

abstract class BaseService
{
    protected string $class;
    protected BaseRepository $repository;

    public function create(array $data): Model
    {
        $resource = $this->fillEntity($data);
        return $this->repository->create($resource);
    }

    public function findAll(int $per_page = null): ?LengthAwarePaginator
    {
        return $this->repository->findAll($per_page);
    }


    public function find(string $id): ?Model
    {
        $resource = $this->repository->find($id);

        if (is_null($resource)) {
            throw new NotFoundResourceException('Resource not found.');
        }

        return $resource;
    }

    public function update(string $id, array $data): Model
    {
        $resource = $this->repository->find($id);

        if (is_null($resource)) {
            throw new NotFoundResourceException('Resource not found.');
        }

        $data = $this->fillEntity($data);

        return $this->repository->update($resource, $data);
    }

    public function delete(string $id): bool
    {
        $removedResources = $this->repository->delete($id);

        if (!$removedResources) {
            throw new NotFoundResourceException('Resource not found.');
        }

        return true;
    }

    public function fillEntity(array $data): Model
    {
        return new $this->class($data);
    }
}
