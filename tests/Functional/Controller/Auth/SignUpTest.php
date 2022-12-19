<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use JsonException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpTest extends WebTestCase
{
	/**
	 * @throws JsonException
	 */
	public function testOkRegister(): void
	{
		$client = self::createClient();
		$payload = [
			'email' => 'xxx@mail.com',
			'password' => '123456'
		];

		$client->request(
			Request::METHOD_POST,
			'auth/sign_up',
			[],
			[],
			[],
			json_encode($payload, JSON_THROW_ON_ERROR)
		);

		self::assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
	}
}