<?php

namespace Tests\Unit\Domain\Services;

use App\Domain\Services\IdGenerator;
use PHPUnit\Framework\TestCase;

class IdGeneratorTest extends TestCase {

    public function testGeneratesUniqueIds() {
        $map = [];
        for ($i = 0; $i < 10000; $i++) {
            $id = IdGenerator::generate();
            $this->assertArrayNotHasKey($id, $map);
            $map[$id] = 1;
        }
    }
}
