<?php

declare(strict_types=1);

namespace App\Repository\Trait;

use App\Entity\BaseEntityInterface;

trait SaveTrait
{
    public function save(BaseEntityInterface $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
