<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

/**
 * Value object representing task priorities
 * @package Domain\Tasks
 */
final class TaskPriority {
    const LOW = 0;
    const AVERAGE = 1;
    const URGENT = 2;

    /**
     * @var self[]
     */
    private static array $instances = [
        null, null, null
    ];

    private static array $names = [
        'LOW', 'AVERAGE', 'URGENT'
    ];

    private int $value;

    private function __construct(int $priority) {
        $this->value = $priority;
    }

    public static function parse(string $priority) {
        $index = array_search($priority, self::$names, true);
        if ($index === null || $index === false) {
            throw new \InvalidArgumentException("Unknow priority '{$priority}'");
        }

        return self::of($index);
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