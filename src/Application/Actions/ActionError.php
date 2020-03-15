<?php
declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionError implements JsonSerializable {
    public const
        BAD_REQUEST = 1,
        INSUFFICIENT_PRIVILEGES = 2,
        METHOD_NOT_ALLOWED = 3,
        NOT_IMPLEMENTED = 4,
        RESOURCE_NOT_FOUND = 5,
        SERVER_ERROR = 6,
        UNAUTHENTICATED = 7,
        VALIDATION_ERROR = 8,
        VERIFICATION_ERROR = 9;

    private static array $typeNames = [
        self::BAD_REQUEST => 'BAD_REQUEST',
        self::INSUFFICIENT_PRIVILEGES => 'INSUFFICIENT_PRIVILEGES',
        self::METHOD_NOT_ALLOWED => 'NOT_ALLOWED',
        self::NOT_IMPLEMENTED => 'NOT_IMPLEMENTED',
        self::RESOURCE_NOT_FOUND => 'RESOURCE_NOT_FOUND',
        self::SERVER_ERROR => 'SERVER_ERROR',
        self::UNAUTHENTICATED => 'UNAUTHENTICATED',
        self::VALIDATION_ERROR => 'VALIDATION_ERROR',
        self::VERIFICATION_ERROR => 'VERIFICATION_ERROR'
    ];

    /**
     * @var int
     */
    private int $type;

    /**
     * @var string
     */
    private string $message;

    /**
     * @param int $type
     * @param string $message
     */
    public function __construct(int $type, string $message = '') {
        $this->type = $type;
        $this->message = $message;
    }

    public function getType(): int {
        return $this->type;
    }

    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'type' => self::$typeNames[$this->type],
            'message' => $this->message,
        ];
    }
}
