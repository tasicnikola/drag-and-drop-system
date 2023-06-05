<?php

namespace App\Exception\NotFound;

class DeskNotFoundException extends NotFoundException
{
    public function __construct(readonly string $deskGuid)
    {
        parent::__construct($deskGuid, 'desk');
    }
}
