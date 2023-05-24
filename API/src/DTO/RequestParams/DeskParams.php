<?php

namespace App\DTO\RequestParams;

use App\DTO\Position;

class DeskParams
{
    public function __construct(
        public readonly string $name,
        public readonly Position $position,
        public readonly ?string $roomGuid,
    ) {
    }
}
