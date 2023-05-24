<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Space;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\RemoveTrait;
use App\Repository\Trait\SaveTrait;

class SpaceRepository extends ServiceEntityRepository implements RepositoryInterface
{
    use SaveTrait;
    use RemoveTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Space::class);
    }

    public function getEntityInstance(): Space
    {
        return new Space();
    }
}
