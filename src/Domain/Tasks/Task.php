<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Common\Values\Date;
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
    public Date $date;
    public TaskPriority $priority;
    public TaskStatus $status;
    public TaskDescription $description;

    private function __construct(
        int $id,
        int $userId,
        Date $date,
        TaskDescription $description,
        TaskPriority $priority,
        TaskStatus $status
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->date = $date;
        $this->description = $description;
        $this->priority = $priority;
        $this->status = $status;
    }

    public static function createNew(
        int $userId,
        Date $date,
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
            $date,
            $description,
            $priority,
            $status
        );
    }

    public static function create(
        int $id,
        int $userId,
        Date $date,
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
        $cur = Date::current();
        if ($status->toInt() !== TaskStatus::COMPLETED && $date->isBefore($cur)) {
            throw new \DomainException("Can not create active task in the past");
        }

        return new self(
            $id,
            $userId,
            $date,
            $description,
            $priority,
            $status
        );
    }

    public function updateDescription(TaskDescription $newDescription): void {
        $this->description = $newDescription;
    }

    public function isPending(): bool {
        return $this->status->toInt() === TaskStatus::PENDING;
    }

    public function isLowPriority(): bool {
        return $this->priority->is(TaskPriority::LOW);
    }

    public function makeUrgent(): void {
        $this->priority = TaskPriority::of(TaskPriority::URGENT);
    }

    public function isUrgent(): bool {
        return $this->priority->is(TaskPriority::URGENT);
    }

    public function complete(): void {
        $this->status = TaskStatus::of(TaskStatus::COMPLETED);
    }

    public function isComplete(): bool {
        return $this->status->is(TaskStatus::COMPLETED);
    }

    public function isInProgress(): bool {
        return $this->status->is(TaskStatus::IN_PROGRESS);
    }

    public function delete(): void {
        $this->status = TaskStatus::of(TaskStatus::DELETED);
    }

    public function isDeleted(): bool {
        return $this->status->is(TaskStatus::DELETED);
    }

    public function reSchedule(Date $newDate): void {
        if ($newDate->isBefore($this->date)) {
            throw new \DomainException("Can not re-schedule to the past");
        }
        $this->date = $newDate;
    }


}