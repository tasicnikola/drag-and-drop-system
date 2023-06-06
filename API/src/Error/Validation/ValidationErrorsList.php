<?php

namespace App\Error\Validation;

use JsonSerializable;

class ValidationErrorsList implements JsonSerializable
{
    private array $errors;

    public function __construct(array $errors = [])
    {
        foreach ($errors as $error) {
            $this->add($error);
        }
    }

    public function add(ValidationError $error): void
    {
        $this->errors[] = $error;
    }

    public function jsonSerialize(): mixed
    {
        return $this->errors;
    }
}
