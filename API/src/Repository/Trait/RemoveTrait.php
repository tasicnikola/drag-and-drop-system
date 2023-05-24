<?php

declare(strict_types=1);

namespace App\Repository\Trait;

use App\Entity\BaseEntityInterface;

trait RemoveTrait
{
    public function remove(BaseEntityInterface $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
