<?php
declare(strict_types=1);

namespace App\Infrastructure\Tasks;

use App\Domain\Common\Values\Date;
use App\Domain\Tasks\{TaskData, TaskPriority, TaskStatus, UserTasksQuery};

class SqliteUserTasksQuery implements UserTasksQuery {

    public const DB_TABLE = 'tasks';

    private string $dbTable = self::DB_TABLE;

    private \SQLite3 $db;

    private int $userId = 0;

    public function __construct(\SQLite3 $db) {
        $this->db = $db;
    }

    public function setUserId(int $id) {
        $this->userId = $id;
    }

    /**
     * @param int $userId
     * @param int $id
     * @return TaskData|null
     */
    public function getTask(int $id): ?TaskData {
        $query = "
            SELECT * 
            FROM {$this->dbTable} 
            WHERE id = {$id} AND user_id = {$this->userId}
        ";
        $res = $this->db->query($query);
        $row = $res->fetchArray(\SQLITE3_ASSOC);
        if ($row === false) {
            return null;
        }

        return $this->mapRow($row);
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
     * @param Date $date
     * @return TaskData[]
     */
    public function getTasksByDate(Date $date): array {
        $query = "
            SELECT * 
            FROM {$this->dbTable} 
            WHERE user_id = {$this->userId}
              AND `date` = '{$date->getTimestamp()}'
            ORDER BY `date` ASC   
        ";

        return $this->getAll($query);
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

    /**
     * @param int $userId
     * @param DateTime $from
     * @param DateTime $to
     * @return TaskData[]
     */
    public function getUserTasksByDateRange(int $userId, Date $from, Date $to): array {
        $query = "
            SELECT * 
            FROM {$this->dbTable} 
            WHERE user_id = {$userId}
              AND `date` >= '{$from->toString()}'
              AND `date` <= '{$to->toString()}'
            ORDER BY `time` ASC   
        ";

        return $this->getAll($query);
    }
}