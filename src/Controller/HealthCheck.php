<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/health-check', name: 'health_check', methods: [Request::METHOD_GET])]
class HealthCheck
{
    public function __invoke(): Response
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
