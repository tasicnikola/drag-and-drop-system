<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Doctrine\Query;

use App\Query\CheckUniqueInterface;
use Doctrine\DBAL\Connection;

class CheckUnique implements CheckUniqueInterface
{
    public function __construct(private readonly Connection $connection)
    {
    }
    public function checkUnique(array $uniqueParams, string $table, ?string $guid): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select($uniqueParams)
            ->from($table);

        if (null !== $guid) {
            $queryBuilder
                ->where('guid != :guid')
                ->setParameter('guid', $guid);
        }
        $data = $queryBuilder->fetchAllAssociative();

        return $data;
    }
}
