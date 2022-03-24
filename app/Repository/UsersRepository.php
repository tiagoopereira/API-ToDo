<?php

namespace App\Repository;

use App\Models\User;

class UsersRepository extends BaseRepository
{
    public function __construct()
    {
        $this->class = User::class;
    }
}
