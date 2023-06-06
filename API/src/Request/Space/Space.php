<?php

namespace App\Request\Space;

use App\DTO\RequestParams\SpaceParams;
use App\Request\Field\Desks;
use App\Request\Field\Dimension;
use App\Request\Field\Guid;
use App\Request\Field\Name;

interface Space extends Guid, Name, Dimension, Desks
{
    public function params(): SpaceParams;
}
