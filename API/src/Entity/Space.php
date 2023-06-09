<?php

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Dimensions;
use App\DTO\RequestParams\SpaceParams;
use App\Repository\SpaceRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\HasGuidTrait;
use App\Entity\Trait\TimestampableTrait;
use JsonSerializable;
use App\Entity\BaseEntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: SpaceRepository::class)]
#[ORM\Table(name: 'spaces')]
#[HasLifecycleCallbacks]
class Space implements JsonSerializable, BaseEntityInterface
{
    use HasGuidTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 64, unique: true)]
    private string $name;

    #[ORM\Column(type: 'json')]
    private array $dimensions;

    #[ORM\OneToMany(targetEntity: Desk::class, mappedBy: 'space', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $desks;

    public function __construct()
    {
        $this->desks = new ArrayCollection([]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDimensions(): Dimensions
    {
        return new Dimensions($this->dimensions['width'], $this->dimensions['height']);
    }

    public function setDimensions(Dimensions $dimensions): self
    {
        $this->dimensions = $dimensions->toArray();

        return $this;
    }

    public function getDesks(): Collection
    {
        return $this->desks;
    }

    public function setDesks(ArrayCollection $desks): self
    {
        $this->desks = $desks;

        return $this;
    }

    public function addDesk(Desk $desk): void
    {
        $this->desks->add($desk);
    }

    public function removeDesk(Desk $desk): void
    {
        $this->desks->removeElement($desk);
    }

    public function syncDesks(array $names): void
    {
        foreach ($this->desks->toArray() as $desk) {
            if (!in_array($desk->getName(), $names)) {
                $this->removeDesk($desk);
            }
        }
    }

    public function jsonSerialize(): mixed
    {
        return [
            'guid' => $this->guid,
            'name' => $this->name,
            'dimensions' => $this->dimensions,
            'desks' => $this->desks,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public function update(SpaceParams $params): void
    {
        $this->name = $params->name;
        $this->dimensions = $params->dimensions->jsonSerialize();
    }
}
