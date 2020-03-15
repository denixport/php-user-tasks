<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Common\Values\Date;

/**
 * TaskData is DTO for task
 * @package App\Domain\Tasks
 */
class TaskData implements \JsonSerializable {
    public int $id;
    public int $userId;
    public Date $date;
    public TaskPriority $priority;
    public TaskStatus $status;
    public string $title;
    public string $description;

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'date' => $this->date->toString(),
            'priority' => $this->priority->toString(),
            'status' => $this->status->toString(),
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}