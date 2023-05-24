<?php

namespace App\DTO\RequestParams\Collection;

use App\DTO\RequestParams\DeskParams;

class DesksParams
{
    public readonly array $params;
    public function __construct(DeskParams ...$desksParams)
    {
        $this->params = $desksParams;
    }
}
