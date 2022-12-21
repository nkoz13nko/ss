<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Tests\Functional\AbstractFunctionalTestCase;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class SignUpTestCase extends AbstractFunctionalTestCase
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
        $this->request('/api/auth/sign_up', Request::METHOD_POST, $payload);
        self::assertResponseIsSuccessful();
    }
}
