<?php

namespace Lox;

use IntlChar;

class Scanner {
    private string $source;
    private array $tokens;
    private int $start = 0;
    private int $current = 0;
    private int $line = 1;

    function __construct(string $source)
    {
        $this->source = $source;
    }

    function scanTokens() : array
    {
        while(!$this->isAtEnd()) {
            // we are at the beginning of the next lexeme
            $this->start = $this->current;
            $this->scanToken();
        }

        $this->tokens []= new Token(TokenType::EOF, "", null, $this->line);
        return $this->tokens;
    }

    private function isAtEnd() : bool
    {
       return $this->current >= strlen($this->source);
    }

    private function scanToken() : void
    {
        $c = $this->advance();
        switch($c) {
            case '(': $this->addToken(TokenType::LEFT_PAREN); break;
            case ')': $this->addToken(TokenType::RIGHT_PAREN); break;
            case '{': $this->addToken(TokenType::LEFT_BRACE); break;
            case '}': $this->addToken(TokenType::RIGHT_BRACE); break;
            case ',': $this->addToken(TokenType::COMMA); break;
            case '.': $this->addToken(TokenType::DOT); break;
            case '-': $this->addToken(TokenType::MINUS); break;
            case '+': $this->addToken(TokenType::PLUS); break;
            case ';': $this->addToken(TokenType::SEMICOLON); break;
            case '*': $this->addToken(TokenType::STAR); break;
        }
    }

    private function advance() : string
    {
        $c = $this->source[$this->current];
        $this->current++;
        return $c;
    }

    private function addToken(TokenType $type) : void
    {
       $this->addTokenLit($type, null);
    }

    private function addTokenLit(TokenType $type, object|null $literal) : void
    {
        $text = substr($this->source, $this->start, $this->current - $this->start);
        $this->tokens []= new Token($type, $text, $literal, $this->line);
    }
}