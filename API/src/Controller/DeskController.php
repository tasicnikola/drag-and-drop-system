<?php

namespace App\Controller;

use App\Controller\Trait\JsonResponseTrait;
use App\Request\Desk\Desk as DeskRequest;
use App\Service\DeskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class DeskController extends AbstractController
{
    use JsonResponseTrait;

    public function __construct(private readonly DeskService $deskService)
    {
    }

    public function getAll(): JsonResponse
    {
        try {
            return $this->jsonResponse($this->deskService->getAll());
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    public function get(string $guid): JsonResponse
    {
        try {
            return $this->jsonResponse($this->deskService->get($guid));
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    public function create(DeskRequest $request): JsonResponse
    {
        try {
            $guid = $this->deskService->create($request->params());

            return $this->jsonResponseCreated($guid);
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    public function update(DeskRequest $request, string $guid): JsonResponse
    {
        try {
            $this->deskService->update($request->params(), $guid);

            return $this->jsonResponseNoContent();
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    public function delete(string $guid): JsonResponse
    {
        try {
            $this->deskService->delete($guid);

            return $this->jsonResponseNoContent();
        } catch (Exception $e) {
            return $this->exceptionJsonResponse($e);
        }
    }
}
