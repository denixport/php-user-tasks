<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Common\Values\DateTime;

interface TaskQuery {
    /**
     * @param int $userId
     * @return TaskData[]
     */
    public function getUserTasks(int $userId): array;

    /**
     * @param int $userId
     * @param DateTime $time
     * @return TaskData[]
     */
    public function getUserDailyTasks(int $userId, DateTime $time): array;
}