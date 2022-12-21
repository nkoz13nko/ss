<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Tests\Functional\AbstractFunctionalTestCase;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class SignUpTest extends AbstractFunctionalTestCase
{
    /**
     * @throws JsonException
     */
    public function testOkRegister(): void
    {
        $payload = [
            'email' => 'xxx@mail.com',
            'password' => '123456'
        ];
        $this->request(Request::METHOD_POST, '/api/auth/sign_up', $payload);
        self::assertResponseIsSuccessful();
    }
}
