<?php

namespace App\Service;

use App\Models\User;
use App\Repository\UsersRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersService extends BaseService
{
    public function __construct(UsersRepository $repository)
    {
        $this->class = User::class;
        $this->repository = $repository;
    }

    public function update(string $id, array $data, string $user_id = null): User
    {
        /** @var User */
        $resource = Auth::user();
        $data = $this->fillEntity($data);

        return $this->repository->update($resource, $data);
    }

    public function delete(string $id, string $user_id = null): bool
    {
        /** @var User */
        $resource = Auth::user();
        $this->repository->delete($resource);

        return true;
    }

    public function fillEntity(array $data): User
    {
        $user = new $this->class($data);
        $user->password = Hash::make($user->password);

        return $user;
    }
}
