<?php

use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TodosControllerTest extends TestCase
{
    use DatabaseMigrations;

    public User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testUserShouldNotCreateATodoWithoutRequiredFields(): void
    {
        $this->actingAs($this->user)->post(route('todos.store'));

        $this->assertResponseStatus(422);
        $this->seeJsonContains(['title' => ['The title field is required.']]);
        $this->seeJsonContains(['user_id' => ['The user id field is required.']]);
    }

    public function testUserShouldNotCreateATodoWithWrongUserId(): void
    {
        $payload = [
            'title' => 'Test',
            'description' => 'Test a create todo route',
            'done' => false,
            'user_id' => 2
        ];

        $this->actingAs($this->user)->post(route('todos.store'), $payload);

        $this->assertResponseStatus(422);
        $this->seeJsonContains(['user_id' => ['The user id must be a valid UUID.']]);
    }

    public function testUserCanCreateATodo(): void
    {
        $payload = [
            'title' => 'Test',
            'description' => 'Test a create todo route',
            'done' => false,
            'done_at' => null,
            'user_id' => $this->user->id
        ];

        $this->actingAs($this->user)->post(route('todos.store'), $payload);

        $this->assertResponseStatus(201);
        $this->seeInDatabase('todos', $payload);
        $this->seeJsonContains($payload);
    }

    public function testUserCanListTodos(): void
    {
        $todos = Todo::factory(2)->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->get(route('todos.index'));

        $this->assertResponseStatus(200);
        $this->seeJsonContains(['id' => $todos[0]->id]);
        $this->seeJsonContains(['id' => $todos[1]->id]);
    }

    public function testUserCanVisualizeATodo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->get(route('todos.show', ['id' => $todo->id]));

        $this->assertResponseStatus(200);
        $this->seeJsonContains(['id' => $todo->id]);
    }

    public function testUserCanUpdateATodo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $payload = [
            'id' => $todo->id,
            'title' => 'test123',
            'description' => null,
            'done' => true,
            'done_at' => new \Datetime('now', new DateTimeZone('America/Sao_Paulo'))
        ];

        $this->actingAs($this->user)->put(route('todos.update', ['id' => $todo->id]), $payload);

        $this->assertResponseStatus(200);
        $this->seeInDatabase('todos', $payload);
    }

    public function testUserCanDeleteATodo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->delete(route('todos.destroy', ['id' => $todo->id]));

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('todos', ['id' => $todo->id]);
    }

    public function testUserShouldNotSetStatusWithInvalidStatus(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->post(route('todo.updateStatus', ['id' => $todo->id, 'status' => 'test']));
        $this->assertResponseStatus(400);
        $this->seeJsonContains([
            'error' => true,
            'message' => 'Available status: done, undone.',
            'code' => 400
        ]);
    }

    public function testUserCanSetTodoStatusDone(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->post(route('todo.updateStatus', ['id' => $todo->id, 'status' => 'done']));
        $this->assertResponseStatus(200);
        $this->seeInDatabase('todos', ['id' => $todo->id, 'done' => true]);
    }

    public function testUserCanSetTodoStatusUndone(): void
    {
        $todo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'done' => true,
            'done_at' => Carbon::now()
        ]);

        $this->actingAs($this->user)->post(route('todo.updateStatus', ['id' => $todo->id, 'status' => 'undone']));
        $this->assertResponseStatus(200);
        $this->seeInDatabase('todos', ['id' => $todo->id, 'done' => false, 'done_at' => null]);
    }
}
