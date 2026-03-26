<?php

declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class RegisterRequest
{
    #[NotBlank]
    #[Email]
    public string $email;

    #[NotBlank]
    #[Length(min: 8)]
    public string $plainPassword;
}
