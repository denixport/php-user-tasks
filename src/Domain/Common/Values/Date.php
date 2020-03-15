<?php
declare(strict_types=1);

namespace App\Domain\Common\Values;


class Date {
    private const NUMDAYS = [
        0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31
    ];

    private int $year;
    private int $month;
    private int $day;

    private function __construct(int $year, int $month, int $day) {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    public static function current() {
        return self::fromDateTime(
            new \DateTime('now', new \DateTimeZone('UTC'))
        );
    }

    public static function fromDateTime(\DateTimeInterface $datetime) {
        return self::fromTimestamp($datetime->getTimestamp());
    }

    public static function fromTimestamp(int $timestamp) {
        $datetime = new \DateTime();
        $datetime->setTimestamp($timestamp);
        list($y, $m, $d) = explode('-', $datetime->format('Y-n-j'), 3);

        return new self((int)$y, (int)$m, (int)$d);
    }

    public static function parse(string $date) {
        list ($y, $m, $d) = explode('-', $date, 3);
        return self::create((int)$y, (int)$m, (int)$d);
    }

    public static function create(int $year, int $month, int $day) {
        if ($year < 0 || $year > 9999) {
            throw new \InvalidArgumentException("Invalid year '{$year}'");
        }
        if ($month < 1 || $month > 12) {
            throw new \InvalidArgumentException("Invalid month '{$month}'");
        }
        $dmax = self::NUMDAYS[$month];
        if ($month === 2 && self::isLeapYear($year)) {
            $dmax = 29;
        }
        if ($day < 1 || $day > $dmax) {
            throw new \InvalidArgumentException("Invalid day '{$day}'");
        }

        return new self($year, $month, $day);
    }

    public static function isLeapYear(int $year) {
        return ($year % 4 == 0) && ($year % 100 != 0 || $year % 16 == 0);
    }

    public function getYear(): int {
        return $this->year;
    }

    public function getMonth(): int {
        return $this->month;
    }

    public function getDay(): int {
        return $this->day;
    }

    public function isBefore(self $other) {
        if ($this->isSameAs($other)) {
            return false;
        }

        return !$this->isAfter($other);
    }

    public function isSameAs(self $other) {
        return $this->day === $other->day && $this->month === $other->month &&
            $this->year === $other->year;
    }

    public function isAfter(self $other): bool {
        if ($this->isSameAs($other)) {
            return false;
        }

        $d = $this->year - $other->year;
        if ($d !== 0) {
            return $d > 0;
        }

        $d = $this->month - $other->month;
        if ($d !== 0) {
            return $d > 0;
        }

        $d = $this->day - $other->day;
        if ($d !== 0) {
            return $d > 0;
        }

        return false;
    }

    public function getTimestamp(): int {
        return $this->toDateTime()->getTimestamp();
    }

    public function toDateTime(): \DateTime {
        $datetime = new \DateTime();
        $datetime->setTimezone(new \DateTimeZone('UTC'));
        $datetime->setDate($this->year, $this->month, $this->day);
        $datetime->setTime(0, 0, 0);

        return $datetime;
    }

    public function toString(): string {
        return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
    }

}