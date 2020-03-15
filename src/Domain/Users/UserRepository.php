<?php
declare(strict_types=1);

namespace App\Domain\Users;

interface UserRepository {
    public function get(int $id): User;

    public function store(User $user): void;

    public function delete(int $id): void;
}