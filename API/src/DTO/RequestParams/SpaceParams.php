<?php

declare(strict_types=1);

namespace App\DTO\RequestParams;

use App\DTO\Dimensions;
use App\DTO\RequestParams\Collection\DesksParams;

class SpaceParams
{
    public function __construct(
        public readonly string $name,
        public readonly Dimensions $dimensions,
        public readonly DesksParams $desks
    ) {
    }
}
