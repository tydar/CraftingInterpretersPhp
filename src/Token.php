<?php

namespace Lox;

use Lox\TokenType;

class Token {
    public TokenType $type;
    public string $lexeme;
    public Literal|null $literal;
    public int $line;

    function __construct(TokenType $type, string $lexeme, Literal|null $literal, int $line)
    {
        $this->type = $type;
        $this->lexeme = $lexeme;
        $this->literal = $literal;
        $this->line = $line;
    }

    function __toString() : string
    {
        $type = $this->type->type();
        return "$type $this->lexeme $this->literal";
    }
}