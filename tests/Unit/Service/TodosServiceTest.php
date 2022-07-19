<?php

use App\Models\Todo;
use App\Repository\TodosRepository;
use App\Service\TodosService;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\MockObject;

class TodosServiceTest extends TestCase
{
    private MockObject $todo;

    private MockObject $repository;

    private TodosService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->todo = $this->createMock(Todo::class);
        $this->repository = $this->createMock(TodosRepository::class);
        $this->service = new TodosService($this->repository);
    }

    public function testUpdateTodoStatusWithWrongStatusThrowsAnException(): void
    {
        $id = Str::uuid()->toString();
        $user_id = Str::uuid()->toString();
        $status = 'wrong';

        $this->repository->method('find')
            ->with($id, $user_id)
            ->willReturn($this->todo);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Available status: done, undone.');

        $this->service->updateStatus($id, $user_id, $status);
    }

    public function testUpdateTodoStatusWithStatusDone(): void
    {
        $id = Str::uuid()->toString();
        $user_id = Str::uuid()->toString();
        $status = 'done';

        $this->repository->method('find')
            ->with($id, $user_id)
            ->willReturn($this->todo);

        $response = $this->service->updateStatus($id, $user_id, $status);

        $this->assertIsObject($response);
        $this->assertInstanceOf(MockObject::class, $response);
    }

    public function testUpdateTodoStatusWithStatusUndone(): void
    {
        $id = Str::uuid()->toString();
        $user_id = Str::uuid()->toString();
        $status = 'undone';

        $this->repository->method('find')
            ->with($id, $user_id)
            ->willReturn($this->todo);

        $response = $this->service->updateStatus($id, $user_id, $status);

        $this->assertIsObject($response);
        $this->assertInstanceOf(MockObject::class, $response);
    }
}
