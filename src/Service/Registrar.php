<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Auth\UserSignUpDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Registrar
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function register(UserSignUpDto $userSignUpDto): User
    {
        if (null !== $this->userRepository->findOneBy(['email' => $userSignUpDto->getEmail()])) {
            throw new LogicException("email already in use");
        }

        $user = new User();
        $user
            ->setEmail($userSignUpDto->getEmail())
            ->setPassword($this->passwordHasher->hashPassword($user, $userSignUpDto->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
