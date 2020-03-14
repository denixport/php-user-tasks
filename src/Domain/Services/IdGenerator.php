<?php


namespace App\Domain\Services;

/**
 * Class IdGenerator
 * @todo Should use hrtime, assume 64bit system
 * @todo Use proper crypto rand
 * @package App\Domain\Service
 */
class IdGenerator {
    private const base = 1_500_000_000_000_000;

    public static function generate(): int {
        $t = (int)(\microtime(true) * 1000000.0);
        $t -= self::base;

        return $t << 16 | rand(0, (1 << 16) - 1);
    }
}