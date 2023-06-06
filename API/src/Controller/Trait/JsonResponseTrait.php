<?php

declare(strict_types=1);

namespace App\Controller\Trait;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

trait JsonResponseTrait
{
    private function jsonResponse(object|array|string $data): JsonResponse
    {
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    private function jsonResponseNoContent(): JsonResponse
    {
        return new JsonResponse("", JsonResponse::HTTP_NO_CONTENT);
    }

    private function jsonResponseCreated(string $guid): JsonResponse
    {
        return new JsonResponse(
            ['guid' => $guid],
            JsonResponse::HTTP_CREATED
        );
    }

    private function exceptionJsonResponse(Exception $e): JsonResponse
    {
        // $code = $e->getCode() ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

        return new JsonResponse(
            [
                'error' => [
                    // 'code' => $code,
                    'message' => $e->getMessage(),
                ],
            ],
            // $code
        );
    }
}
