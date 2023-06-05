<?php

namespace App\Request\Desk;

use App\DTO\RequestParams\DeskParams;
use App\Request\Field\Guid;
use App\Request\Field\Name;
use App\Request\Field\Position;
use App\Request\Field\SpaceGuid;

interface Desk extends Guid, Name, SpaceGuid, Position
{
    public function params(): DeskParams;
}
