<?php

declare(strict_types=1);

namespace App\Exception\NotFound;

class SpaceNotFoundException extends NotFoundException
{
    public function __construct(readonly string $spaceGuid)
    {
        parent::__construct($spaceGuid, 'space');
    }
}
