<?php

declare(strict_types=1);

namespace App\DTO;

use App\DTO\Collection\Desks;
use DateTime;
use DateTimeImmutable;
use JsonSerializable;

class SpaceWithDesks implements JsonSerializable
{
    public function __construct(
        public string $guid,
        public string $name,
        public Dimensions $dimension,
        public ?Desks $desks,
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
            'desks' => $this->desks,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
