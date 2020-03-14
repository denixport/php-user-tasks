<?php

namespace App\Infrastructure\Tasks;

use App\Domain\Common\Values\DateTime;
use App\Domain\Tasks\{TaskData, TaskPriority, TasksQuery, TaskStatus};


class TasksSqliteQuery implements TasksQuery {

    private \SQLite3 $db;

    private string $table = 'tasks';

    public function __construct(\SQLite3 $db) {
        $this->db = $db;
    }

    /**
     * @param int $userId
     * @param int $id
     * @return TaskData|null
     */
    public function getSingleUserTask(int $userId, int $id): ?TaskData {
        $query = "
            SELECT * 
            FROM {$this->table} 
            WHERE id = {$id} AND user_id = {$userId}
        ";
        $res = $this->db->query($query);
        $row = $res->fetchArray(\SQLITE3_ASSOC);
        if ($row === false) {
            return null;
        }

        return $this->mapRow($row);
    }

    /**
     * @param string $query
     * @return TaskData[]
     */
    private function getAll(string $query): array {
        $res = $this->db->query($query);
        if ($res === false) {
            throw new \RuntimeException('DB Error:' . $this->db->lastErrorMsg());
        }
        $result = [];
        while ($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $result[] = $this->mapRow($row);
        }
        return $result;
    }

    private function mapRow(array $row): TaskData {
        $td = new TaskData();
        $td->id = (int)$row['id'];
        $td->userId = (int)$row['user_id'];
        $td->time = DateTime::fromDateTime(new \DateTime($row['time']));
        $td->priority = TaskPriority::of((int)$row['priority']);
        $td->status = TaskStatus::of((int)$row['status']);
        $td->title = $row['title'];
        $td->description = $row['description'];

        return $td;
    }

    /**
     * @param int $userId
     * @param DateTime $time
     * @return TaskData[]
     */
    public function getUserTasksByDate(int $userId, DateTime $time): array {
        $query = "
            SELECT * 
            FROM {$this->table} 
            WHERE user_id = {$userId}
              AND `time` = '{$time->toString()}'
            ORDER BY `time` ASC   
        ";

        return $this->getAll($query);
    }

    /**
     * @param int $userId
     * @param DateTime $from
     * @param DateTime $to
     * @return TaskData[]
     */
    public function getUserTasksByDateRange(int $userId, DateTime $from, DateTime $to): array {
        $query = "
            SELECT * 
            FROM {$this->table} 
            WHERE user_id = {$userId}
              AND `time` >= '{$from->toString()}'
              AND `time` <= '{$to->toString()}'
            ORDER BY `time` ASC   
        ";

        return $this->getAll($query);
    }
}