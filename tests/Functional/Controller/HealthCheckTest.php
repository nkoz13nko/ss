<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use JsonException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class HealthCheckTest extends WebTestCase
{
    /**
     * @throws JsonException
     */
    public function testOk(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/health-check');
        /** @var string $json */
        $json = $client->getResponse()->getContent();
        /** @var string[] $response */
        $response = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseIsSuccessful();
        $this->assertEquals('ok', $response['status']);
    }
}
