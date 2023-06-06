<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Trait\JsonResponseTrait;
use App\Request\Space\Space as SpaceRequest;
use App\Service\SpaceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class SpaceController extends AbstractController
{
    use JsonResponseTrait;

    public function __construct(private readonly SpaceService $service)
    {
    }

    public function getAll(): JsonResponse
    {
        try {
            return $this->jsonResponse($this->service->getAll());
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    public function get(string $guid): JsonResponse
    {
        try {
            return $this->jsonResponse($this->service->get($guid));
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    public function create(SpaceRequest $request): JsonResponse
    {
        try {
            $guid = $this->service->create($request->params());
            return $this->jsonResponseCreated($guid);
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    public function update(string $guid, SpaceRequest $request): JsonResponse
    {
        try {
            $this->service->updateByGuid($guid, $request->params());

            return $this->jsonResponseNoContent();
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    public function delete(string $guid): JsonResponse
    {
        try {
            $this->service->deleteByGuid($guid);

            return $this->jsonResponseNoContent();
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }
}
