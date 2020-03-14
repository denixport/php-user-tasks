<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

interface TaskRepository {
    public function get(int $id): Task;

    public function store(Task $task): void;
}