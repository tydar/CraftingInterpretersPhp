<?php

namespace Lox;

enum LiteralType
{
    case STRING;
    case IDENTIFIER;
    case NUMBER;
    
    public function type() : string
    {
        return match($this) {
            LiteralType::STRING => "LITERAL_STRING",
            LiteralType::IDENTIFIER => "LITERAL_IDENTIFIER",
            LiteralType::NUMBER => "LITERAL_NUMBER"
        };
    }
}