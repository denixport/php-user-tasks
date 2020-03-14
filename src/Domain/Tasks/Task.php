<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Common\Values\DateTime;
use App\Domain\Services\IdGenerator;

class Task {
    const VERSION = 1;

    /**
     * @var int
     */
    public int $id;
    /**
     * @var int
     */
    public int $userId;
    public DateTime $time;
    public TaskPriority $priority;
    public TaskStatus $status;
    public TaskDescription $description;

    private function __construct(
        int $id,
        int $userId,
        DateTime $time,
        TaskDescription $description,
        TaskPriority $priority,
        TaskStatus $status
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->time = $time;
        $this->description = $description;
        $this->priority = $priority;
        $this->status = $status;
    }

    public static function createNew(
        int $userId,
        DateTime $time,
        TaskDescription $description,
        TaskPriority $priority = null
    ) {
        $id = IdGenerator::generate();

        if ($priority === null) {
            $priority = TaskPriority::of(TaskPriority::AVERAGE);
        }

        $status = TaskStatus::of(TaskStatus::PENDING);

        return self::create(
            $id,
            $userId,
            $time,
            $description,
            $priority,
            $status
        );
    }

    public static function create(
        int $id,
        int $userId,
        DateTime $time,
        TaskDescription $description,
        TaskPriority $priority,
        TaskStatus $status
    ) {
        if ($id === null || $id <= 0) {
            throw new \DomainException("Invalid Task ID '{$userId}'");
        }

        if ($userId === null || $userId <= 0) {
            throw new \DomainException("Invalid Task User ID '{$userId}'");
        }

        // Business rule: can create only completed tasks in the past
        $mark = DateTime::fromDateTime(new \DateTime('-1 min'));
        if ($status->toInt() !== TaskStatus::COMPLETED && $time->isBefore($mark)) {
            throw new \DomainException("Can not create active task in the past");
        }

        return new self(
            $id,
            $userId,
            $time,
            $description,
            $priority,
            $status
        );
    }

    public function updateDescription(TaskDescription $newDescription): void {
        $this->description = $newDescription;
    }

    public function isPending() : bool{
        return $this->status->toInt() === TaskStatus::PENDING;
    }

    public function isLowPriority() : bool {
        return $this->priority->toInt() === TaskPriority::LOW;
    }

    public function makeUrgent(): void {
        $this->priority = TaskPriority::of(TaskPriority::URGENT);
    }

    public function isUrgent() : bool {
        return $this->priority->toInt() === TaskPriority::URGENT;
    }

    public function complete(): void {
        $this->status = TaskStatus::of(TaskStatus::COMPLETED);
    }

    public function isComplete() : bool{
        return $this->status->toInt() === TaskStatus::COMPLETED;
    }

    public function isInProgress(): bool {
       return $this->status->toInt() === TaskStatus::IN_PROGRESS;
    }

    public function delete(): void {
        $this->status = TaskStatus::of(TaskStatus::DELETED);
    }

    public function isDeleted() : bool{
        return $this->status->toInt() === TaskStatus::DELETED;
    }

    public function reSchedule(DateTime $newTime): void {
        if ($newTime->isBefore($this->time)) {
            throw new \DomainException("Can not re-schedule to the past");
        }
        $this->time = $newTime;
    }


}