<?php

namespace App\DTO;

use App\Entity\Space;
use DateTime;
use DateTimeImmutable;
use JsonSerializable;

class Desk implements JsonSerializable
{
    public function __construct(
        public readonly string $guid,
        public readonly string $name,
        public readonly Position $position,
        public readonly ?Space $space,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?DateTime $updatedAt
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'guid' => $this->guid,
            'name' => $this->name,
            'position' => $this->position,
            'space' => $this->space,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }

    public static function fromArray(array $data): self
    {
        $position = new Position(
            $data['position']['x'] ?? 0,
            $data['position']['y'] ?? 0,
            $data['position']['angle'] ?? 0
        );

        return new self(
            $data['guid'] ?? '',
            $data['name'] ?? '',
            $position,
            null,
            new DateTimeImmutable(),
            null
        );
    }
}
