<?php
declare(strict_types=1);

namespace App\Infrastructure\Tasks;

use App\Domain\Common\Values\DateTime;
use App\Domain\Tasks\{Task, TaskDescription, TaskPriority, TaskRepository, TaskStatus};

class SqliteTaskRepository implements TaskRepository {
    public const DB_TABLE = 'tasks';

    private string $dbTable = self::DB_TABLE;

    /**
     * @var \SQLite3
     */
    private \SQLite3 $db;

    public function __construct(\SQLite3 $db) {
        $this->db = $db;
    }

    public function get(int $id): Task {
        $query = "SELECT * FROM {$this->dbTable} WHERE id = {$id} AND is_active = 1";
        $res = $this->db->query($query);
        $row = $res->fetchArray(\SQLITE3_ASSOC);
        if ($row === false) {
            throw new \RuntimeException('DB Error: No task record found');
        }

        return Task::create(
            (int)$row['id'],
            (int)$row['user_id'],
            DateTime::fromDateTime(new \DateTime($row['time'])),
            new TaskDescription($row['title'], $row['description']),
            TaskPriority::of((int)$row['priority']),
            TaskStatus::of((int)$row['status'])
        );
    }

    public function store(Task $task): void {
        $query = "
            INSERT INTO {$this->dbTable} 
            (id, is_active, status, priority, user_id, date. title, description, updated_at)
            VALUES (
                {$task->id},
                1,
                {$task->status->toInt()},
                {$task->priority->toInt()},
                {$task->userId},
                '{$task->time->toString()}',
                '{$task->description->getTitle()}',
                '{$task->description->getText()}',
                date('now')
            ) ON CONFLICT(id) DO UPDATE 
            SET 
                status = {$task->status->toInt()},
                priority = {$task->priority->toInt()},
                user_id = {$task->userId},
                date = '{$task->time->toString()}',
                title = '{$task->description->getTitle()}',
                description = '{$task->description->getText()}',
                updated_at = date('now')           
        ";
        $res = $this->db->exec($query);
        if (!$res) {
            throw new \RuntimeException('DB Error:' . $this->db->lastErrorMsg());
        }
    }

    public function delete(int $id): void {
        $query = "UPDATE {$this->dbTable} SET is_active = 0 WHERE id = {$id}";
        $res = $this->db->exec($query);
        if (!$res) {
            throw new \RuntimeException('DB Error:' . $this->db->lastErrorMsg());
        }
    }
}