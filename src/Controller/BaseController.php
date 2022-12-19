<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api')]
abstract class BaseController extends AbstractController
{
    protected function validateUuid(string $uuid): void
    {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestHttpException('invalid uuid');
        }
    }

    protected function badRequest(ConstraintViolationListInterface $violationList): JsonResponse
    {
        $errors = [];
        foreach ($violationList as $violation) {
            $errors[] = $violation->getMessage();
        }

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
