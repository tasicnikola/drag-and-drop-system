<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

class Position implements JsonSerializable
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly int $angle
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'angle' => $this->angle
        ];
    }

    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'angle' => $this->angle
        ];
    }
}
