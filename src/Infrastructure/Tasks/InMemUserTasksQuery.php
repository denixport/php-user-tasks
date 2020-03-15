<?php

namespace App\Infrastructure\Tasks;

use App\Domain\Common\Values\Date;
use App\Domain\Tasks\{Task, TaskData, UserTasksQuery};


class InMemUserTasksQuery implements UserTasksQuery {

    private InMemTaskRepository $repo;

    private array $storage = [];

    private int $userId = 0;

    public function __construct(InMemTaskRepository $repo) {
        $this->repo = $repo;
    }

    public function setUserId(int $id) {
        $this->userId = $id;
    }

    /**
     * @param int $userId
     * @param int $id
     * @return TaskData|null
     */
    public function getTask(int $id): ?TaskData {
        $this->updateStorage();

        if (!isset($this->storage[$id])) {
            return null;
        }

        $td = $this->storage[$id];
        if ($td->userId !== $this->userId) {
            return null;
        }

        return $td;
    }

    private function updateStorage() {
        $this->storage = [];
        foreach ($this->repo->getAll() as $task) {
            $td = $this->mapTask($task);
            $this->storage[$td->id] = $td;
        }
    }

    private function mapTask(Task $task): TaskData {
        $td = new TaskData();
        $td->id = $task->id;
        $td->userId = $task->userId;
        $td->date = $task->date;
        $td->priority = $task->priority;
        $td->status = $task->status;
        $td->title = $task->description->getTitle();
        $td->description = $task->description->getText();

        return $td;
    }

    /**
     * @param int $userId
     * @param Date $date
     * @return TaskData[]
     */
    public function getTasksByDate(Date $date): array {
        $this->updateStorage();
        $result = [];
        foreach ($this->storage as $td) {
            /** @var TaskData $td */
            if ($td->userId === $this->userId && $td->date->isSameAs($date)) {
                $result[] = $td;
            }
        }
        return $result;
    }

    /**
     * @param int $userId
     * @param Date $from
     * @param Date $to
     * @return TaskData[]
     */
    public function getUserTasksByDateRange(int $userId, Date $from, Date $to): array {

    }
}