<?php

namespace App\Api\Login;

use Symfony\Component\Validator\Constraints as Assert;

class ApiLoginCredentialsLoginRequest
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 1024)]
    public string $password;
}