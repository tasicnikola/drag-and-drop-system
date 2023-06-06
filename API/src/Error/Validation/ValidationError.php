<?php

namespace App\Error\Validation;

use JsonSerializable;

class ValidationError implements JsonSerializable
{
    public function __construct(
        private readonly string $property,
        private readonly string $message
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'property' => $this->property,
            'message' => $this->message
        ];
    }
}
