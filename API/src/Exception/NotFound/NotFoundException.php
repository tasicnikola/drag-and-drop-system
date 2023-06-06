<?php

namespace App\Exception\NotFound;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotFoundException extends Exception
{
    public function __construct(readonly string $guid, readonly string $entityName)
    {
        parent::__construct("There is no {$entityName} with guid: {$guid}", JsonResponse::HTTP_NOT_FOUND);
    }
}
