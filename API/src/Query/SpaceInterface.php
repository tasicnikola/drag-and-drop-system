<?php

declare(strict_types=1);

namespace App\Query;

use App\DTO\Collection\Spaces;
use App\DTO\SpaceWithDesks;

interface SpaceInterface
{
    public function getAll(): Spaces;

    public function getByGuid(string $guid): SpaceWithDesks|null;
}
