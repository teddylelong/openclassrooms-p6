<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AccountNotVerifiedAuthenticationException extends AuthenticationException
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct();
        $this->message = "Ce compte n'a pas encore été vérifié";
    }
}