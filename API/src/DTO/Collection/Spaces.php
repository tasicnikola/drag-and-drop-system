<?php

declare(strict_types=1);

namespace App\DTO\Collection;

use JsonSerializable;

class Spaces implements JsonSerializable
{
    public function __construct(
        public readonly array $spaces
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return $this->spaces;
    }
}
