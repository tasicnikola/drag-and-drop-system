<?php

declare(strict_types=1);

namespace App\DTO\Collection;

use JsonSerializable;

class Desks implements JsonSerializable
{
    public function __construct(
        public readonly array $desks = []
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return $this->desks;
    }
}
