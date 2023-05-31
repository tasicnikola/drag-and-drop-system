<?php

namespace App\Entity;

use App\DTO\Position as PositionDTO;
use App\DTO\RequestParams\DeskParams;
use App\Repository\DeskRepository;
use App\Entity\Trait\GuidTrait;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: DeskRepository::class)]
#[ORM\Table(name: 'desks')]
#[ORM\HasLifecycleCallbacks]
class Desk implements JsonSerializable, BaseEntityInterface
{
    use GuidTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 64, unique:true)]
    private string $name;

    #[ORM\Column(type: 'json')]
    private array $position;

    #[ORM\ManyToOne(targetEntity:'Space', inversedBy: 'desks')]
    #[ORM\JoinColumn(name:'space', referencedColumnName:'guid')]
    private Space $space;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): PositionDTO
    {
        return new PositionDTO($this->position['x'], $this->position['y'], $this->position['angle']);
    }


    public function setPosition(PositionDTO $position): self
    {
        $this->position = $position->toArray();

        return $this;
    }

    public function getSpace(): Space
    {
        return $this->space;
    }

    public function setSpace(Space $space): self
    {
        $this->space = $space;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'guid' => $this->guid,
            'name' => $this->name,
            'position' => $this->position,
            'space' => $this->space,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }

    public function update(DeskParams $deskParams, Space $space): void
    {
        $this->name = $deskParams->name;
        $this->space = $space;
        $this->position = $deskParams->position->toArray();
    }
}
