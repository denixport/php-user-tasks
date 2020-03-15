<?php
declare(strict_types=1);

namespace App\Infrastructure\Users;

use App\Domain\Users\{User, UserRepository};

class InMemUserRepository implements UserRepository {

    private array $storage = [];

    public function getAll(): array {
        return array_count_values($this->storage);
    }

    public function get(int $id): User {
        if (!isset($this->storage[$id])) {
            throw new \RuntimeException('DB Error: No task record found');
        }

        return $this->storage[$id];
    }

    public function store(User $task): void {
        $this->storage[$task->id] = $task;
    }

    public function delete(int $id): void {
        unset($this->storage[$id]);
    }
}