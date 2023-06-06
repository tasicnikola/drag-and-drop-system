<?php

namespace App\Exception\Exists;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class Exists extends Exception
{
    public function __construct(readonly string $value, readonly string $entityName, readonly string $property)
    {
        parent::__construct("There is already {$entityName} with {$property}: {$value}", JsonResponse::HTTP_BAD_REQUEST);
    }
}
