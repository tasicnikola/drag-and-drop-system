<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Rfc4122\UuidInterface;
use Ramsey\Uuid\Lazy\LazyUuidFromString;

trait HasGuidTrait
{
    #[ORM\Id]
    #[Orm\GeneratedValue(strategy: 'CUSTOM')]
    #[Orm\Column(type: 'uuid', unique: true)]
    #[Orm\CustomIdGenerator(class: UuidGenerator::class)]
    private $guid;

    public function getGuid(): LazyUuidFromString|UuidInterface|string
    {
        return $this->guid;
    }
    

    public function setGuid(UuidInterface|string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }
}
