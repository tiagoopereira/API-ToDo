<?php

namespace App\Service;

use App\Models\User;
use App\Repository\UsersRepository;
use Illuminate\Support\Facades\Hash;

class UsersService extends BaseService
{
    public function __construct(
        UsersRepository $repository
    )
    {
        $this->class = User::class;
        $this->repository = $repository;
    }

    public function create(array $data): User
    {
        $user = $this->fillEntity($data);
        $user->password = Hash::make($user->password);

        return $this->repository->create($user);
    }
}
