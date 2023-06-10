<?php

declare(strict_types=1);

namespace App\DTO;

use DateTime;
use DateTimeImmutable;
use JsonSerializable;

class Space implements JsonSerializable
{
    public function __construct(
        public string $guid,
        public string $name,
        public Dimensions $dimension,
        public DateTimeImmutable $createdAt,
        public ?DateTime $updatedAt,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return  [
            'guid' => $this->guid,
            'name' => $this->name,
            'dimension' => $this->dimension,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
