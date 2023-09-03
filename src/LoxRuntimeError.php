<?php

namespace Lox;

use Exception;

class LoxRuntimeError extends Exception {
    public Token $token;

    function __construct(Token $token, string $message) {
        parent::__construct($message);
        $this->token = $token;
    }
}