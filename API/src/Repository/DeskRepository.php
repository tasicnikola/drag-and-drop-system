<?php

namespace App\Repository;

use App\Entity\Desk;
use App\Repository\Trait\RemoveTrait;
use App\Repository\Trait\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DeskRepository extends ServiceEntityRepository implements RepositoryInterface
{
    use SaveTrait;
    use RemoveTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Desk::class);
    }

    public function getEntityInstance(): Desk
    {
        return new Desk();
    }
}
