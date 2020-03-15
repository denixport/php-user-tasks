<?php

namespace Tests\Unit\Domain\Common\Values;

use App\Domain\Common\Values\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase {

    public function testCanBeCreatedFromYMD() {
        $dt = Date::create(1970, 1, 1);
        $this->assertEquals(1970, $dt->getYear());
        $this->assertEquals(1, $dt->getMonth());
        $this->assertEquals(1, $dt->getDay());
        $this->assertEquals('1970-01-01', $dt->toString());

        $dt = Date::create(2038, 1, 19);
        $this->assertEquals('2038-01-19', $dt->toString());
    }

    public function testCanBeCreatedFromTimestamp() {
        $dt = Date::fromTimestamp(0);
        $this->assertEquals('1970-01-01', $dt->toString());

        $dt = Date::fromTimestamp(2147483647 + 1);
        $this->assertEquals('2038-01-19', $dt->toString());
    }

    public function testCanBeCreatedFromPHPDateTime() {
        $local = new \DateTime(
            '2000-01-01 00:00:00',
            new \DateTimeZone('Europe/Luxembourg')
        );

        $utc = Date::fromDateTime($local)->toDateTime();

        $this->assertEquals('1999-12-31', $utc->format('Y-m-d'));
    }

    public function testComparesCorrectly() {
        $d0 = Date::create(2038, 1, 18);
        $d1 = Date::create(2038, 1, 18);
        $d2 = Date::create(2038, 1, 19);

        $this->assertTrue($d2->isAfter($d1));
        $this->assertTrue($d1->isBefore($d2));

        $this->assertTrue($d1->isSameAs($d0));
        $this->assertFalse($d1->isBefore($d0));
        $this->assertFalse($d1->isAfter($d0));
    }
}
