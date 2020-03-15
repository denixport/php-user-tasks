<?php
declare(strict_types=1);

namespace Tests\Functional\Domain\Tasks;

use App\Domain\Common\Values\Date;
use App\Domain\Tasks\{Task, TaskDescription, TaskPriority};
use App\Infrastructure\Tasks\InMemTaskRepository;
use PHPUnit\Framework\TestCase;

class TaskRepositoryTest extends TestCase {

    private InMemTaskRepository $repo;

    public function setUp(): void {
        $this->repo = new InMemTaskRepository();
    }

    public function testCanStoreAndGetTask() {
        $task = $this->makeSimpleTask();

        $this->repo->store($task);
        $storedTask = $this->repo->get($task->id);

        $this->assertEquals($task, $storedTask);
    }

    private function makeSimpleTask(): Task {
        return Task::createNew(
            999,
            Date::current(),
            new TaskDescription('New Taks', 'New Task Description'),
            TaskPriority::of(TaskPriority::LOW),
            );
    }

    public function testCanStoreAndDeleteTask() {
        $task = $this->makeSimpleTask();

        $this->repo->store($task);
        $this->repo->delete($task->id);

        $this->expectException(\RuntimeException::class);
        $this->repo->get($task->id);
    }
}