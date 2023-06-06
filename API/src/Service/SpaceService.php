<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Collection\Spaces;
use App\DTO\RequestParams\DeskParams;
use App\DTO\RequestParams\SpaceParams;
use App\DTO\SpaceWithDesks;
use App\Entity\Desk;
use App\Entity\Space;
use App\Exception\NotFound\SpaceNotFoundException;
use App\Query\SpaceInterface;
use App\Repository\DeskRepository;
use App\Repository\SpaceRepository;
use Doctrine\Common\Collections\ArrayCollection;

class SpaceService
{
    public function __construct(
        private readonly SpaceRepository $spaceRepository,
        private readonly SpaceInterface $query,
        private readonly DeskRepository $deskRepository
    ) {
    }

    public function getAll(): Spaces
    {
        return $this->query->getAll();
    }

    public function get(string $guid): SpaceWithDesks
    {
        $space = $this->query->getByGuid($guid);

        if (null === $space) {
            throw new SpaceNotFoundException($guid);
        }

        return $space;
    }

    public function create(SpaceParams $params): string
    {
        $space = $this->spaceRepository->getEntityInstance();
        $desks =  new ArrayCollection();

        foreach ($params->desks->params as $deskParams) {
            $desk = $this->deskRepository->getEntityInstance();
            $desk->update($deskParams, $space);
            $desks->add(
                $desk
            );
        }
        $space->setDesks($desks);
        $space->update($params);
        $this->spaceRepository->save($space);

        return $space->getGuid();
    }

    public function updateByGuid(string $guid, SpaceParams $params): void
    {
        $space = $this->getSpaceEntity($guid);

        foreach ($params->desks->params as $deskParams) {
            $desk = $space->getDesks()->findFirst(fn(int $index, Desk $desk) => $desk->getName() === $deskParams->name);

            if (!$desk) {
                $desk = $this->deskRepository->getEntityInstance();
                $space->addDesk($desk);
            }

            $desk->update($deskParams, $space);
        }

        $space->update($params);
        $space->syncDesks(array_map(fn(DeskParams $deskParams) => $deskParams->name, $params->desks->params));

        $this->spaceRepository->save($space);
    }


    public function deleteByGuid(string $guid): void
    {
        $space = $this->getSpaceEntity($guid);

        $this->spaceRepository->remove($space);
    }


    private function getSpaceEntity(string $guid): Space
    {
        $space = $this->spaceRepository->find($guid);

        if (null === $space) {
            throw new SpaceNotFoundException($guid);
        }

        return $space;
    }
}
