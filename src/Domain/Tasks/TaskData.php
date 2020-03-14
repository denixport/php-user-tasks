<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Common\Values\DateTime;

/**
 * TaskData is DTO for task
 * @package App\Domain\Tasks
 */
class TaskData {
    public int $id;
    public int $userId;
    public DateTime $time;
    public TaskPriority $priority;
    public TaskStatus $status;
    public string $title;
    public string $description;
}