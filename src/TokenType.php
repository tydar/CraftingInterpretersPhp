<?php

namespace Lox;

enum TokenType
{
    // Single-character tokens
    case LEFT_PAREN;
    case RIGHT_PAREN;
    case LEFT_BRACE;
    case RIGHT_BRACE;
    case COMMA;
    case DOT;
    case MINUS;
    case PLUS;
    case SEMICOLON;
    case SLASH;
    case STAR;

    // One or two character tokens
    case BANG;
    case BANG_EQUAL;
    case EQUAL;
    case EQUAL_EQUAL;
    case GREATER;
    case GREATER_EQUAL;
    case LESS;
    case LESS_EQUAL;

    // Literals
    case IDENTIFIER;
    case STRING;
    case NUMBER;

    // Keywords
    case AND;
    case CLASS_TOK;
    case ELSE;
    case FALSE;
    case FUN;
    case FOR;
    case IF;
    case NIL;
    case OR;
    case PRINT;
    case RETURN;
    case SUPER;
    case THIS;
    case TRUE;
    case VAR;
    case WHILE;

    case EOF;

    // PHP enums are not __toStringable...
    public function type() : string {
        return match($this) {
            // Single-character tokens
            TokenType::LEFT_PAREN => "LEFT_PAREN",
            TokenType::RIGHT_PAREN => "RIGHT_PAREN",
            TokenType::LEFT_BRACE => "LEFT_BRACE",
            TokenType::RIGHT_BRACE => "RIGHT_BRACE",
            TokenType::COMMA => "COMMA",
            TokenType::DOT => "DOT",
            TokenType::MINUS => "MINUS",
            TokenType::PLUS => "PLUS",
            TokenType::SEMICOLON => "SEMICOLON",
            TokenType::SLASH => "SLASH",
            TokenType::STAR => "STAR",

            // One or two character tokens
            TokenType::BANG => "BANG",
            TokenType::BANG_EQUAL => "BANG_EQUAL",
            TokenType::EQUAL => "EQUAL",
            TokenType::EQUAL_EQUAL => "EQUAL_EQUAL",
            TokenType::GREATER => "GREATER",
            TokenType::GREATER_EQUAL => "GREATER_EQUAL",
            TokenType::LESS => "LESS",
            TokenType::LESS_EQUAL => "LESS_EQUAL",

            // Literals
            TokenType::IDENTIFIER => "IDENTIFIER",
            TokenType::STRING => "STRING",
            TokenType::NUMBER => "NUMBER",

            // Keywords
            TokenType::AND => "AND",
            TokenType::CLASS_TOK => "CLASS_TOK",
            TokenType::ELSE => "ELSE",
            TokenType::FALSE => "FALSE",
            TokenType::FUN => "FUN",
            TokenType::FOR => "FOR",
            TokenType::IF => "IF",
            TokenType::NIL => "NIL",
            TokenType::OR => "OR",
            TokenType::PRINT => "PRINT",
            TokenType::RETURN => "RETURN",
            TokenType::SUPER => "SUPER",
            TokenType::THIS => "THIS",
            TokenType::TRUE => "TRUE",
            TokenType::VAR => "VAR",
            TokenType::WHILE => "WHILE",

            TokenType::EOF => "EOF",
        };
    }
}