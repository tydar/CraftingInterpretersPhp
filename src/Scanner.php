<?php

namespace Lox;

use IntlChar;

class Scanner
{
    private string $source;
    private array $tokens;

    private int $start = 0;
    private int $current = 0;
    private int $line = 1;

    public const KEYWORDS = [
        "and" => TokenType::AND,
        "class" => TokenType::CLASS_TOK,
        "else" => TokenType::ELSE,
        "false" => TokenType::FALSE,
        "for" => TokenType::FOR,
        "fun" => TokenType::FUN,
        "if" => TokenType::IF,
        "nil" => TokenType::NIL,
        "or" => TokenType::OR,
        "print" => TokenType::PRINT,
        "return" => TokenType::RETURN,
        "super" => TokenType::SUPER,
        "this" => TokenType::THIS,
        "true" => TokenType::TRUE,
        "var" => TokenType::VAR,
        "while" => TokenType::WHILE
    ];

    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function scanTokens(): array
    {
        while(!$this->isAtEnd()) {
            // we are at the beginning of the next lexeme
            $this->start = $this->current;
            $this->scanToken();
        }

        $this->tokens [] = new Token(TokenType::EOF, "", null, $this->line);
        return $this->tokens;
    }

    private function isAtEnd(): bool
    {
        return $this->current >= strlen($this->source);
    }

    private function scanToken(): void
    {
        $c = $this->advance();
        switch($c) {
            case '(': $this->addToken(TokenType::LEFT_PAREN);
                break;
            case ')': $this->addToken(TokenType::RIGHT_PAREN);
                break;
            case '{': $this->addToken(TokenType::LEFT_BRACE);
                break;
            case '}': $this->addToken(TokenType::RIGHT_BRACE);
                break;
            case ',': $this->addToken(TokenType::COMMA);
                break;
            case '.': $this->addToken(TokenType::DOT);
                break;
            case '-': $this->addToken(TokenType::MINUS);
                break;
            case '+': $this->addToken(TokenType::PLUS);
                break;
            case ';': $this->addToken(TokenType::SEMICOLON);
                break;
            case '*': $this->addToken(TokenType::STAR);
                break;
            case '!':
                $type = $this->match('=') ? TokenType::BANG_EQUAL : TokenType::BANG;
                $this->addToken($type);
                break;
            case '=':
                $type = $this->match('=') ? TokenType::EQUAL_EQUAL : TokenType::EQUAL;
                $this->addToken($type);
                break;
            case '<':
                $type = $this->match('=') ? TokenType::LESS_EQUAL : TokenType::LESS;
                $this->addToken($type);
                break;
            case '>':
                $type = $this->match('=') ? TokenType::GREATER_EQUAL : TokenType::GREATER;
                $this->addToken($type);
                break;
            case '/':
                if($this->match('/')) {
                    while($this->peek() != "\n" && !$this->isAtEnd()) {
                        $this->advance();
                    }
                } else {
                    $this->addToken(TokenType::SLASH);
                }
                break;
            case ' ':
            case "\r":
            case "\t":
                // ignore whitespace
                break;
            case "\n":
                $this->line++;
                break;
            case '"':
                $this->string();
                break;
            default:
                if(ctype_digit($c)) {
                    $this->number();
                } elseif($this->isAlpha($c)) {
                    $this->identifier();
                } else {
                    Lox::error($this->line, "Unrecognized character.");
                }
                break;
        }
    }

    private function advance(): string
    {
        $c = $this->source[$this->current];
        $this->current++;
        return $c;
    }

    private function match(string $expected): bool
    {
        if($this->isAtEnd()) {
            return false;
        }
        if($this->source[$this->current] != $expected) {
            return false;
        }

        $this->current++;
        return true;
    }

    private function peek(): string
    {
        if($this->isAtEnd()) {
            return "\0";
        }
        return $this->source[$this->current];
    }

    private function peekNext(): string
    {
        if($this->current + 1 >= strlen($this->source)) {
            return "\0";
        }
        return $this->source[$this->current + 1];
    }

    private function string(): void
    {
        while($this->peek() != '"' && !$this->isAtEnd()) {
            if($this->peek() == "\n") {
                $this->line++;
            }
            $this->advance();
        }

        if($this->isAtEnd()) {
            Lox::error($this->line, 'Unterminated string.');
            return;
        }

        // Get the closing "
        $this->advance();
        $value = substr($this->source, $this->start + 1, $this->current - $this->start - 2);
        $literal = new Literal(LiteralType::STRING, $value);
        $this->addTokenLit(TokenType::STRING, $literal);
    }

    private function number(): void
    {
        while(ctype_digit($this->peek())) {
            $this->advance();
        }

        // see if we have a fractional part
        if($this->peek() == '.' && ctype_digit($this->peekNext())) {
            $this->advance();

            while(ctype_digit($this->peek())) {
                $this->advance();
            }
        }

        $value = substr($this->source, $this->start, $this->current - $this->start);
        $literal = new Literal(LiteralType::NUMBER, floatval($value));
        $this->addTokenLit(TokenType::NUMBER, $literal);
    }

    private function identifier(): void
    {
        while($this->isAlphaNumeric($this->peek())) {
            $this->advance();
        }

        $text = substr($this->source, $this->start, $this->current - $this->start);
        $type = isset(Scanner::KEYWORDS[$text]) ? Scanner::KEYWORDS[$text] : TokenType::IDENTIFIER;

        $this->addToken($type);
    }

    private function isAlpha(string $c): bool
    {
        return ctype_alpha($c) || $c == '_';
    }

    private function isAlphaNumeric(string $c): bool
    {
        return ctype_alnum($c) || $c == '_';
    }

    private function addToken(TokenType $type): void
    {
        $this->addTokenLit($type, null);
    }

    private function addTokenLit(TokenType $type, Literal|null $literal): void
    {
        $text = substr($this->source, $this->start, $this->current - $this->start);
        $this->tokens [] = new Token($type, $text, $literal, $this->line);
    }
}
