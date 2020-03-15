<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Infrastructure\Tasks\{SqliteTaskRepository};

class DbInitializer {
    private \SQLite3 $db;

    public function __construct(\SQLite3 $db) {
        $this->db = $db;
    }

    public function initTaskRepository() {
        $table = SqliteTaskRepository::DB_TABLE;
        $sql = "
            CREATE TABLE '{$table}' (
                'id'	INTEGER NOT NULL CHECK(id >0) UNIQUE,
                'is_active'	INTEGER NOT NULL DEFAULT 0 CHECK(is_active = 0 || is_active=1),
                'status'	INTEGER NOT NULL DEFAULT 0 CHECK(status >= 0),
                'priority'	INTEGER NOT NULL DEFAULT 0 CHECK(priority>=0),
                'user_id'	INTEGER NOT NULL CHECK(user_id > 0),
                'date'	INTEGER NOT NULL CHECK(date > 0),
                'title'	TEXT NOT NULL,
                'description'	TEXT NOT NULL,
                'updated_at'	INTEGER NOT NULL CHECK(updated_at > 0),
                PRIMARY KEY('id')
            )
        ";

        $sql = "CREATE INDEX 'active_idx' ON '{$table}' ('is_active'	DESC)";

    }
}