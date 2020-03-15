<?php


namespace App\Infrastructure\Tasks;

use App\Domain\Tasks\{Task, TaskRepository};


class InMemTaskRepository implements TaskRepository {

    private array $storage = [];

    public function getAll(): array {
        return \array_values($this->storage);
    }

    public function get(int $id): Task {
        if (!isset($this->storage[$id])) {
            throw new \RuntimeException('DB Error: No task record found');
        }

        return $this->storage[$id];
    }

    public function store(Task $task): void {
        $this->storage[$task->id] = $task;
    }

    public function delete(int $id): void {
        unset($this->storage[$id]);
    }
}