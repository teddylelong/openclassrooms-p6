<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AccountNotVerifiedAuthenticationException extends AuthenticationException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct();
        $this->message = "Ce compte n'a pas encore été vérifié";
    }
}