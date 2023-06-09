<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Doctrine\Query;

use App\DTO\Collection\Desks;
use App\DTO\Collection\Spaces;
use App\DTO\Dimensions;
use App\DTO\SpaceWithDesks;
use App\Query\SpaceInterface;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

class Space implements SpaceInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Desk $deskQuery
    ) {
    }

    public function getAll(): Spaces
    {
        $spacesData =  $this->connection->createQueryBuilder('spaces')
            ->select(
                'd.guid as desk_guid',
                'd.name as desk_name',
                'd.position as desk_position',
                'd.created_at as desk_created_at',
                'd.updated_at as desk_updated_at',
                's.guid as space_guid',
                's.name as space_name',
                's.dimensions as space_dimensions',
                's.created_at as space_created_at',
                's.updated_at as space_updated_at',
            )
            ->from('spaces', 's')
            ->innerJoin('s', 'desks', 'd', 's.guid = d.space')
            ->fetchAllAssociative();

        $spacesWithDesks = [];
        foreach ($spacesData as $spaceData) {
            $spaceGuid = $spaceData['space_guid'];
            if (!isset($spacesWithDesks[$spaceGuid])) {
                $spacesWithDesks[$spaceGuid] = [
                    'data' => $spaceData,
                    'desks' => [],
                ];
            }
            $spacesWithDesks[$spaceGuid]['desks'][] = $this->deskQuery->createDeskDTO($spaceData);
        }

        $spaceDTOs = [];
        foreach ($spacesWithDesks as $spaceWithDesksData) {
            $spaceDTOs[] = $this->createSpaceDTO($spaceWithDesksData['data'], new Desks($spaceWithDesksData['desks']));
        }

        return new Spaces($spaceDTOs);
    }

    public function getByGuid(string $guid): ?SpaceWithDesks
    {
        $spaceData = $this->connection->createQueryBuilder('spaces')
            ->select(
                'd.guid as desk_guid',
                'd.name as desk_name',
                'd.position as desk_position',
                'd.created_at as desk_created_at',
                'd.updated_at as desk_updated_at',
                's.guid as space_guid',
                's.name as space_name',
                's.dimensions as space_dimensions',
                's.created_at as space_created_at',
                's.updated_at as space_updated_at',
            )
            ->from('spaces', 's')
            ->innerJoin('s', 'desks', 'd', 's.guid = d.space')
            ->where('s.guid = :guid')
            ->setParameter('guid', $guid)
            ->fetchAllAssociative();

        if (empty($spaceData)) {
            return null;
        }

        $desksArray = [];

        foreach ($spaceData as $deskData) {
            $desksArray[] = $this->deskQuery->createDeskDTO($deskData);
        }

        return $this->createSpaceDTO($spaceData[0], new Desks($desksArray));
    }

    private function createSpaceDTO(array $spaceData, Desks $desks): SpaceWithDesks
    {
        $decodedDimensions = json_decode($spaceData['space_dimensions'], true);

        return new SpaceWithDesks(
            $spaceData['space_guid'],
            $spaceData['space_name'],
            new Dimensions($decodedDimensions['width'], $decodedDimensions['height']),
            $desks,
            new DateTimeImmutable($spaceData['space_created_at']),
            $spaceData['space_updated_at'] ? new DateTime($spaceData['space_updated_at']) : null
        );
    }
}
