<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Image;
use App\Enum\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ImageController extends BaseController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/images', methods: Request::METHOD_POST)]
    public function createImage(
        Request $request,
        EntityManagerInterface $entityManager,
        NormalizerInterface $normalizer
    ): JsonResponse {
		if (!$this->isGranted(UserRole::ROLE_ADMIN)) {
			throw new AccessDeniedHttpException();
		}

        /** @var UploadedFile|null $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if (!$this->isEnabledType($uploadedFile)) {
            throw new BadRequestHttpException('"file" type must be one of: PNG, JPEG');
        }

        $image = (new Image())
            ->setImageFile($uploadedFile)
            ->setImageName($uploadedFile->getFilename())
            ->setImageSize($uploadedFile->getSize());

        $entityManager->persist($image);
        $entityManager->flush();

        return new JsonResponse($normalizer->normalize($image), Response::HTTP_CREATED);
    }

    private function isEnabledType(UploadedFile $uploadedFile): bool
    {
        $mimeType = $uploadedFile->getClientMimeType();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        return in_array($mimeType, ['image/png', 'image/jpeg'], true) && in_array($extension, ['jpg', 'jpeg', 'png'], true);
    }
}
