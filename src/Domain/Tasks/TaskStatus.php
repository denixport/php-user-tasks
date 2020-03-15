<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

/**
 * Value object representing task priorities
 * @package Domain\Tasks
 */
final class TaskStatus {
    public const
        DELETED = 0,
        PENDING = 1,
        IN_PROGRESS = 2,
        COMPLETED = 3;

    private static array $names = [
        'DELETED', 'PENDING', 'IN_PROGRESS', 'COMPLETED'
    ];

    private static array $instances = [
        null, null, null, null
    ];

    private int $value;

    private function __construct(int $priority) {
        $this->value = $priority;
    }

    public static function parse(string $status) {
        $index = array_search($status, self::$names, true);
        if ($index === null || $index === false) {
            throw new \InvalidArgumentException("Unknow status '{$status}'");
        }

        return self::of($index);
    }

    public static function of(int $status): self {
        if ($status < self::DELETED || $status > self::COMPLETED) {
            throw new \InvalidArgumentException("Unknow status value");
        }

        if (self::$instances[$status] === null) {
            self::$instances[$status] = new self($status);
        }

        return self::$instances[$status];
    }

    public function is(int $value) {
        return $this->value === $value;
    }

    public function equals(self $other) {
        return $this->value === $other->value;
    }

    public function toInt() {
        return $this->value;
    }

    public function toString() {
        return self::$names[$this->value];
    }
}