<?php

declare(strict_types=1);

namespace App\DTO\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class UserSignUpDto
{
    /**
     * @Assert\NotBlank
     */
    private string $email;

    /**
     * @Assert\NotBlank
     */
    private string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
