<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Common\Values\DateTime;

/**
 * TaskData is DTO for task
 * @package App\Domain\Tasks
 */
class TaskData implements \JsonSerializable {
    public int $id;
    public int $userId;
    public DateTime $time;
    public TaskPriority $priority;
    public TaskStatus $status;
    public string $title;
    public string $description;

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'time' => $this->time->toString(),
            'priority' => $this->priority->toString(),
            'status' => $this->status->toString(),
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}