<?php

namespace App\Service;

use App\DTO\Collection\Desks;
use App\DTO\Desk as DeskDTO;
use App\DTO\RequestParams\DeskParams;
use App\Exception\NotFound\DeskNotFoundException;
use App\Exception\NotFound\SpaceNotFoundException;
use App\Query\DeskInterface;
use App\Repository\DeskRepository;
use App\Repository\SpaceRepository;

class DeskService
{
    public function __construct(
        private readonly DeskRepository $deskRepository,
        private readonly SpaceRepository $spaceRepository,
        private readonly DeskInterface $deskQuery
    ) {
    }

    public function getAll(): Desks
    {
        return $this->deskQuery->getAll();
    }

    public function get(string $guid): DeskDTO
    {
        $desk = $this->deskQuery->getByGuid($guid);

        if (!$desk) {
            throw new DeskNotFoundException($guid);
        }

        return $desk;
    }

    public function create(DeskParams $deskParams): string
    {
        $space = $this->spaceRepository->find($deskParams->spaceGuid);

        if (!$space) {
            throw new SpaceNotFoundException($deskParams->spaceGuid);
        }

        $desk = $this->deskRepository->getEntityInstance();
        $desk->update($deskParams, $space);
        $this->deskRepository->save($desk);

        return $desk->getGuid();
    }

    public function update(DeskParams $deskParams, string $guid): void
    {
        $desk = $this->deskRepository->find($guid);

        if (!$desk) {
            throw new DeskNotFoundException($guid);
        }

        $space = $this->spaceRepository->find($deskParams->spaceGuid);

        if (!$space) {
            throw new SpaceNotFoundException($deskParams->spaceGuid);
        }

        $desk->update($deskParams, $space);
        $this->deskRepository->save($desk);
    }

    public function delete(string $guid): void
    {
        $desk = $this->deskRepository->find($guid);
        if (!$desk) {
            throw new DeskNotFoundException($guid);
        }
        $this->deskRepository->remove($desk, true);
    }
}
