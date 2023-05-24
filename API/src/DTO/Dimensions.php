<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

class Dimensions implements JsonSerializable
{
    public function __construct(
        public readonly int $width,
        public readonly int $height,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
        ];
    }

    public function toArray(): mixed
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
