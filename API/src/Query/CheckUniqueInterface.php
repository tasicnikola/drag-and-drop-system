<?php

declare(strict_types=1);

namespace App\Query;

interface CheckUniqueInterface
{
    public function checkUnique(array $uniqueParams, string $table, string|null $guid): array;
}
