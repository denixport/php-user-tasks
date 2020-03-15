<?php
declare(strict_types=1);

namespace Tests\Unit\Domain\Common\Values;

use App\Domain\Common\Values\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase {

    public function testCanBeCreatedFromTimestamp() {
        $dt = DateTime::fromTimestamp(0);
        $this->assertEquals('1970-01-01T00:00:00+0000', $dt->toString());

        $dt = DateTime::fromTimestamp(2147483647 + 1);
        $this->assertEquals('2038-01-19T03:14:08+0000', $dt->toString());
    }

    public function testCanBeCreatedFromPHPDateTime() {
        $local = new \DateTime('now', new \DateTimeZone('Europe/Luxembourg'));

        $dt = DateTime::fromDateTime($local);
        $utc = $dt->toDateTime();

        $this->assertEquals($utc->getTimestamp(), $local->getTimestamp());
        $this->assertEquals(3600, $local->getTimezone()->getOffset($utc));
    }

}