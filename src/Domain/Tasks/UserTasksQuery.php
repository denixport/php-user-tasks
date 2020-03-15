<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Common\Values\Date;

interface UserTasksQuery {

    public function setUserId(int $id);

    public function getTask(int $id): ?TaskData;

    public function getTasksByDate(Date $date): array;

}