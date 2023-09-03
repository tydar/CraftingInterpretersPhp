<?php

namespace Lox;

enum LiteralType
{
    case STRING;
    case IDENTIFIER;
    case NUMBER;
    case BOOL;
    case NIL;

    public function type(): string
    {
        return match($this) {
            LiteralType::STRING => "LITERAL_STRING",
            LiteralType::IDENTIFIER => "LITERAL_IDENTIFIER",
            LiteralType::NUMBER => "LITERAL_NUMBER",
            LiteralType::BOOL => "LITERAL_BOOL",
            LiteralType::NIL => "LITERAL_NIL"
        };
    }
}
