<?php
declare(strict_types=1);

namespace App\Domain\Common\Values;


class Email {
    private const RX_LOCAL = '/^[a-zA-Z0-9\-\_\.]{2,}$/';
    private const RX_DOMAIN = '/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/';

    private string $local;
    private string $domain;

    private function __construct(string $local, string $domain) {
        $this->local = $local;
        $this->domain = $domain;
    }

    public static function parse(string $email) {
        list ($local, $domain) = explode('@', $email, 2);

        if (preg_match(self::RX_LOCAL, $local) == 0) {
            throw new \InvalidArgumentException('Invalid local part of email');
        }

        if (preg_match(self::RX_DOMAIN, $domain) == 0) {
            throw new \InvalidArgumentException('Invalid domain part of email');
        }

        return new self($local, $domain);
    }

    public function geLocalPart(): string {
        return $this->local;
    }

    public function getDomain(): string {
        return $this->domain;
    }

    public function sameDomainAs(self $other): bool {
        return $this->domain === $other->domain;
    }

    public function toString(): string {
        return "{$this->local}@{$this->domain}";
    }

}