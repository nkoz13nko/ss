<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFunctionalTestCase extends WebTestCase
{
    protected EntityManagerInterface $entityManager;
    protected KernelBrowser $unauthorizedClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->unauthorizedClient = self::createClient();

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->unauthorizedClient->getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager = $entityManager;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @throws JsonException
     * @param string[] $payload
     */
    protected function request(string $route, string $method, ?array $payload = null): void
    {
        $this->unauthorizedClient->request(
            $method,
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @throws JsonException
     * @return string[]
     */
    protected function extractResponse(KernelBrowser $client): array
    {
        /** @var string $content */
        $content = $client->getResponse()->getContent();
        /** @var string[] $result */
        $result =  json_decode($content, false, 512, JSON_THROW_ON_ERROR);

        return $result;
    }
}
