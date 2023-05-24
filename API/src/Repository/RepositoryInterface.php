<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BaseEntityInterface;

interface RepositoryInterface
{
    public function save(BaseEntityInterface $entity, bool $flush = true): void;

    public function remove(BaseEntityInterface $entity, bool $fulsh = true): void;

    public function getEntityInstance(): BaseEntityInterface;
}
