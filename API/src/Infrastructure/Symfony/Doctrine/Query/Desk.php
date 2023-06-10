<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Doctrine\Query;

use App\DTO\Collection\Desks;
use App\DTO\Desk as DeskDTO;
use App\DTO\Space;
use App\DTO\Dimensions;
use App\DTO\Position;
use App\Query\DeskInterface;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

class Desk implements DeskInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function getAll(): Desks
    {
        $desksData =  $this->connection->createQueryBuilder('desks')
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
            ->from('desks', 'd')
            ->innerJoin('d', 'spaces', 's', 's.guid = d.space')
            ->fetchAllAssociative();

        return new Desks(array_map(fn (array $deskData) => $this->createDeskDTO($deskData), $desksData));
    }

    public function getByGuid(string $guid): ?DeskDTO
    {

        $deskData = $this->connection->createQueryBuilder()
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
            ->from('desks', 'd')
            ->innerJoin('d', 'spaces', 's', 's.guid = d.space')
            ->where('d.guid = :guid')
            ->setParameter('guid', $guid)
            ->fetchAssociative();

        if (false === $deskData) {
            return null;
        }

        return $this->createDeskDTO($deskData);
    }

    public function getDesksByGuids(array $deskGuids): Desks
    {
        $desksData = $this->connection->createQueryBuilder()
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
            ->from('desks', 'd')
            ->innerJoin('d', 'spaces', 's', 's.guid = d.space')
            ->where('d.guid IN (:guids)')
            ->setParameter('guids', $deskGuids, Connection::PARAM_STR_ARRAY)
            ->fetchAllAssociative();

        return new Desks(array_map(fn (array $deskData) => $this->createDeskDTO($deskData), $desksData));
    }

    public function createDeskDTO(array $deskData): DeskDTO
    {
        $decodedPosition = json_decode($deskData['desk_position'], true);
        $decodedDimensions = json_decode($deskData['space_dimensions'], true);
    
        return new DeskDTO(
            $deskData['desk_guid'],
            $deskData['desk_name'],
            new Position(
                $decodedPosition['x'],
                $decodedPosition['y'],
                $decodedPosition['angle']
            ),
            new Space(
                $deskData['space_guid'],
                $deskData['space_name'],
                new Dimensions($decodedDimensions['width'], $decodedDimensions['height']),
                new DateTimeImmutable($deskData['space_created_at']),
                $deskData['space_updated_at'] ? new DateTime($deskData['space_updated_at']) : null
            ),
            new DateTimeImmutable($deskData['desk_created_at']),
            $deskData['desk_updated_at'] ? new DateTime($deskData['desk_updated_at']) : null
        );
    }
    
}
