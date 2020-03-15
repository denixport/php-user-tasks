<?php
declare(strict_types=1);

namespace App\Domain\Common\Values;


class PersonalName {

    private string $firstName;
    private string $lastName;

    private function __construct(string $firstName, string $lastName) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public static function create(string $firstName, string $lastName) {
        // @todo validation

        return new self($firstName, $lastName);
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function toString(): string {
        return "{$this->firstName} {$this->lastName}";
    }

}