<?php
declare(strict_types=1);

namespace App\Domain\Common\Values;


class DateTime {
    private \DateTimeImmutable $datetime;

    private function __construct(\DateTimeImmutable $datetime) {
        $this->datetime = $datetime;
    }

    public static function now() {
        return new self(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
    }

    public static function fromDate(int $year, int $month, int $day) {
        $datetime = new \DateTime(null, new \DateTimeZone('UTC'));
        $datetime->setDate($year, $month, $day);
        return new self(\DateTimeImmutable::createFromMutable($datetime));
    }

    public static function fromDateTime(\DateTimeInterface $datetime) {
        return self::fromTimestamp($datetime->getTimestamp());
    }

    public static function fromTimestamp(int $timestamp) {
        $datetime = new \DateTime();
        $datetime->setTimestamp($timestamp);
        $datetime->setTimezone(new \DateTimeZone('UTC'));
        return new self(\DateTimeImmutable::createFromMutable($datetime));
    }

    public function year(): int {
        return (int)$this->datetime->format('Y');
    }

    public function month(): int {
        return (int)$this->datetime->format('m');
    }

    public function day(): int {
        return (int)$this->datetime->format('d');
    }

    public function equals(self $other) {
        return $this->datetime === $other->datetime;
    }

    public function isAfter(self $other) {
        return $this->datetime > $other->datetime;
    }

    public function isBefore(self $other) {
        return $this->datetime < $other->datetime;
    }

    public function toDateTime() {
        return $this->datetime;
    }

    public function toString() {
        return $this->datetime->format(\DATE_ISO8601);
    }

}