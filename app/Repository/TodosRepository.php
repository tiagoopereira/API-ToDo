<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Models\Todo;

class TodosRepository extends BaseRepository
{
    public function __construct()
    {
        $this->class = Todo::class;
    }

    public function done(Todo $todo): Todo
    {
        $todo->update([
            'done' => true,
            'done_at' => Carbon::now()
        ]);

        return $todo;
    }

    public function undone(Todo $todo): Todo
    {
        $todo->update([
            'done' => false,
            'done_at' => null
        ]);

        return $todo;
    }
}
