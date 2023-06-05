<?php

declare(strict_types=1);

namespace App\Query;

use App\DTO\Collection\Desks;
use App\DTO\Desk;

interface DeskInterface
{
    public function getAll(): Desks;

    public function getByGuid(string $guid): ?Desk;
}
