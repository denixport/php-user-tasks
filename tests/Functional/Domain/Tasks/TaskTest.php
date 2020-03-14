<?php

namespace Tests\Functional\Domain\Tasks;

use App\Domain\Common\Values\DateTime;
use App\Domain\Tasks\{Task, TaskDescription, TaskPriority, TaskStatus};
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase {

    private function makeSimpleTask() : Task {
        return Task::createNew(
            999,
            DateTime::now(),
            new TaskDescription('New Taks', 'New Task Description'),
            TaskPriority::of(TaskPriority::LOW),
        );
    }

    public function testNewTaskGetsId() {
        $task = $this->makeSimpleTask();

        $this->assertTrue($task->id > 0);

        $this->assertTrue($task->isLowPriority());
        $this->assertTrue($task->isPending());
    }

    public function testCanNotCreateActiveTasksInThePast() {
        $this->expectException(\DomainException::class);
        $task = Task::createNew(
            999,
            DateTime::fromDateTime(new \DateTime('-1 day')),
            new TaskDescription('New Taks', 'New Task Description'),
            TaskPriority::of(TaskPriority::LOW)
        );
    }

    public function testUpdatesPriority() {
        $task = $this->makeSimpleTask();

        $task->makeUrgent();
        $this->assertTrue($task->isUrgent());
    }

    public function testUpdatesStatus() {
        $task = $this->makeSimpleTask();

        $task->complete();
        $this->assertTrue($task->isComplete());

        $task->delete();
        $this->assertTrue($task->isDeleted());
    }

}
