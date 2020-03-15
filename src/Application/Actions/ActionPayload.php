<?php
declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable {
    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var array|object|ActionError|null
     */
    private $data;

    /**
     * @param int $statusCode
     * @param array|object|ActionError|null $data
     */
    public function __construct(int $statusCode = 200, $data = null) {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * @return array|object|ActionError|null
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        $payload = [
            'statusCode' => $this->statusCode,
        ];

        if ($this->data instanceof ActionError) {
            $payload['error'] = $this->data;
        } else if ($this->data !== null) {
            $payload['data'] = $this->data;
        }

        return $payload;
    }
}
