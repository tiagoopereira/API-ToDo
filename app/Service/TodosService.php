<?php

namespace App\Service;

use App\Interfaces\RepositoryInterface;
use App\Models\Todo;
use App\Repository\TodosRepository;

class TodosService extends BaseService
{
    /** @var TodoRepository */
    protected RepositoryInterface $repository;

    public function __construct(TodosRepository $repository)
    {
        $this->repository = $repository;
        $this->class = Todo::class;
    }

    public function updateStatus(string $id, string $user_id, string $status): Todo
    {
        $todo = $this->find($id, $user_id);

        return match ($status) {
            'done' => $this->repository->done($todo),
            'undone' => $this->repository->undone($todo),
            default => throw new \InvalidArgumentException('Available status: done, undone.')
        };
    }
}
