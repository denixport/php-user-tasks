<?php
declare(strict_types=1);

namespace App\Domain\Users;

use App\Domain\Common\Values\{Email, PersonalName};
use App\Domain\Services\IdGenerator;

class User {
    const VERSION = 1;

    public int $id;

    public Email $email;

    public PersonalName $name;

    private function __construct(int $id, Email $email, PersonalName $name) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
    }

    public static function createNew(Email $email, PersonalName $name): User {
        $id = IdGenerator::generate();

        return self::create($id, $email, $name);
    }

    public static function create(int $id, Email $email, PersonalName $name): User {
        return new self($id, $email, $name);
    }

}