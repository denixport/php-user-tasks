<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

/**
 * Value object representing task priorities
 * @package Domain\Tasks
 */
class TaskStatus {
    const PENDING = 0;
    const COMPLETED = 1;
    const DELETED = 9;

    private static $instances = [
        self::PENDING => null,
        self::COMPLETED => null,
        self::DELETED => null,
    ];

    private int $value;

    private function __construct(int $priority) {
        $this->value = $priority;
    }

    public static function of(int $status): self {
        if ($status < self::PENDING || $status > self::DELETED) {
            throw new \InvalidArgumentException("Unknow status value");
        }

        if (self::$instances[$status] === null) {
            self::$instances[$status] = new self($status);
        }

        return self::$instances[$status];
    }

    public function toInt() {
        return $this->value;
    }

    public function isCompleted() {
        return $this->value === self::COMPLETED;
    }

    public function equals(self $other) {
        return $this->value === $other->value;
    }
}