<?php

namespace App\Service;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function __construct(
        UserRepository $repository
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
