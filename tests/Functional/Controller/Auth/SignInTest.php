<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Entity\User;
use App\Tests\Functional\AbstractFunctionalTestCase;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class SignInTest extends AbstractFunctionalTestCase
{
    /**
     * @throws JsonException
     */
    public function testSignInOk(): void
    {
        $email = 'email@mail.ru';
        $password = '123456';
        $user = (new User())
            ->setEmail($email)
            ->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $payload = [
            'email' => $email,
            'password'=> $password
        ];

        $this->request(Request::METHOD_POST, '/api/auth/sign_in', $payload);
        $response = $this->extractResponse($this->unauthorizedClient);
        self::assertResponseIsSuccessful();
        self::assertArrayHasKey('token', $response);
    }
}
