<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Rfc4122\UuidInterface;

trait GuidTrait
{
    #[ORM\Id]
    #[Orm\GeneratedValue(strategy: 'CUSTOM')]
    #[Orm\Column(type: 'uuid', unique: true)]
    #[Orm\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string $guid;

    public function getGuid(): UuidInterface|string
    {
        return $this->guid;
    }

    public function setGuid(UuidInterface|string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }
}
