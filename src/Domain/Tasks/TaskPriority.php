<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

/**
 * Value object representing task priorities
 * @package Domain\Tasks
 */
class TaskPriority {
    const LOW = 0;
    const AVERAGE = 1;
    const URGENT = 9;

    /**
     * @var self[]
     */
    private static $instances = [
        self::LOW => null,
        self::AVERAGE => null,
        self::URGENT => null,
    ];

    private int $value;

    private function __construct(int $priority) {
        $this->value = $priority;
    }

    public static function of(int $priority): self {
        if ($priority < self::LOW || $priority > self::URGENT) {
            throw new \InvalidArgumentException("Unknow priority value");
        }

        if (self::$instances[$priority] === null) {
            self::$instances[$priority] = new self($priority);
        }

        return self::$instances[$priority];
    }

    public function toInt() {
        return $this->value;
    }

    public function isUrgent() {
        return $this->value === self::URGENT;
    }


    public function equals(self $other) {
        return $this->value === $other->value;
    }
}