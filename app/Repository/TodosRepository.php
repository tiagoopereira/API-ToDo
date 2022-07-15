<?php

namespace App\Repository;

use App\Models\Todo;
use Carbon\Carbon;

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
            'done_at' => Carbon::now(),
        ]);

        return $todo;
    }

    public function undone(Todo $todo): Todo
    {
        $todo->update([
            'done' => false,
            'done_at' => null,
        ]);

        return $todo;
    }
}
