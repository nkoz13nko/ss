<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Auth\UserSignUpDto;
use App\Entity\User;
use App\Service\Registrar;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/auth')]
class Auth extends BaseController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/sign_up', name: 'sign_up', methods: [Request::METHOD_POST])]
    public function signUp(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        Registrar $registrar,
        NormalizerInterface $normalizer
    ): JsonResponse {
        try {
            $rawData = $request->getContent();

            if (!$rawData) {
                throw new BadRequestHttpException("request is empty");
            }

            /** @var UserSignUpDto $userSignUpDto */
            $userSignUpDto = $serializer->deserialize($rawData, UserSignUpDto::class, 'json');
            $violations = $validator->validate($userSignUpDto);
            if ($violations->count() > 0) {
                return $this->badRequest($violations);
            }
            $user = $registrar->register($userSignUpDto);
            return new JsonResponse(
                $normalizer->normalize($user, 'json', [AbstractNormalizer::GROUPS => [User::USER_GROUP]]),
                Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return new JsonResponse(["errors" => [$exception->getMessage()]], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/sign_in', name: 'sign_in', methods: [Request::METHOD_POST])]
    public function signIn(UserInterface $user, JWTTokenManagerInterface $JWTTokenManager): JsonResponse
    {
        return new JsonResponse(['token' =>$JWTTokenManager->create($user)]);
    }
}
