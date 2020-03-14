<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Common\Values\DateTime;

interface TasksQuery {

    public function getSingleUserTask(int $userId, int $id): ?TaskData;

    public function getUserTasksByDate(int $userId, DateTime $time): array;

    public function getUserTasksByDateRange(int $userId, DateTime $from, DateTime $to): array;
}