<?php

namespace Lox;

use Exception;

class Literal
{
    public LiteralType $type;
    public string|float|bool|null $value;

    public function __construct(LiteralType $type, string|float|bool|null $value)
    {
        // blow up if we have mismatched $type and $value -- this shouldn't happen
        if(($type == LiteralType::STRING || $type == LiteralType::IDENTIFIER) && gettype($value) != 'string') {
            throw new Exception("Internal lexer error: mismatched literal type and value");
        }

        if($type == LiteralType::NUMBER && gettype($value) != 'double') {
            throw new Exception("Internal lexer error: mismatched literal type and value");
        }

        if($type == LiteralType::BOOL && gettype($value) != 'boolean') {
            throw new Exception("Internal lexer error: mismatched literal type and value");
        }

        if($type == LiteralType::NIL && !is_null($value)) {
            throw new Exception("Internal lexer error: mismatched literal type and value");
        }

        $this->type = $type;
        $this->value = $value;
    }

    public function __toString(): string
    {
        if($this->type == LiteralType::BOOL) {
            return $this->value ? 'true' : 'false';
        }

        return "$this->value";
    }
}
