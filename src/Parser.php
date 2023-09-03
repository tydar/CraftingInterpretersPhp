<?php

namespace Lox;

class Parser
{
    private array $tokens;
    private int $current = 0;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function parse(): ?Expr
    {
        try {
            return $this->expression();
        } catch (LoxParseError $e) {
            return null;
        }
    }

    private function expression(): Expr
    {
        return $this->equality();
    }

    private function equality(): Expr
    {
        $expr = $this->comparison();

        while($this->match(TokenType::BANG_EQUAL, TokenType::EQUAL_EQUAL)) {
            $operator = $this->previous();
            $right = $this->comparison();
            $expr = new Binary($expr, $operator, $right);
        }

        return $expr;
    }

    private function comparison(): Expr
    {
        $expr = $this->term();

        while($this->match(TokenType::GREATER, TokenType::GREATER_EQUAL, TokenType::LESS, TokenType::LESS_EQUAL)) {
            $operator = $this->previous();
            $right = $this->term();
            $expr = new Binary($expr, $operator, $right);
        }

        return $expr;
    }

    private function term(): Expr
    {
        $expr = $this->factor();

        while($this->match(TokenType::MINUS, TokenType::PLUS)) {
            $operator = $this->previous();
            $right = $this->factor();
            $expr = new Binary($expr, $operator, $right);
        }

        return $expr;
    }

    private function factor(): Expr
    {
        $expr = $this->unary();

        while($this->match(TokenType::STAR, TokenType::SLASH)) {
            $operator = $this->previous();
            $right = $this->unary();
            $expr = new Binary($expr, $operator, $right);
        }

        return $expr;
    }

    private function unary(): Expr
    {
        if($this->match(TokenType::BANG, TokenType::MINUS)) {
            $operator = $this->previous();
            $right = $this->unary();
            return new Unary($operator, $right);
        }

        return $this->primary();
    }

    private function primary(): Expr
    {
        if($this->match(TokenType::FALSE)) {
            return new LiteralAst(new Literal(LiteralType::BOOL, false));
        }
        if($this->match(TokenType::TRUE)) {
            return new LiteralAst(new Literal(LiteralType::BOOL, true));
        }
        if($this->match(TokenType::NIL)) {
            return new LiteralAst(new Literal(LiteralType::NIL, null));
        }

        if($this->match(TokenType::NUMBER)) {
            return new LiteralAst(new Literal(LiteralType::NUMBER, $this->previous()->literal->value));
        }

        if($this->match(TokenType::STRING)) {
            return new LiteralAst(new Literal(LiteralType::STRING, $this->previous()->literal->value));
        }

        if($this->match(TokenType::LEFT_PAREN)) {
            $expr = $this->expression();
            $this->consume(TokenType::RIGHT_PAREN, "Expect ')' after expression.");
            return new Grouping($expr);
        }

        throw $this->error($this->peek(), "Expect expression.");
    }

    private function match(TokenType ... $types): bool
    {
        foreach($types as $type) {
            if($this->check($type)) {
                $this->advance();
                return true;
            }
        }
        return false;
    }

    private function consume(TokenType $type, string $message): Token
    {
        if($this->check($type)) {
            return $this->advance();
        }

        throw $this->error($this->peek(), $message);
    }

    private function check(TokenType $type): bool
    {
        if($this->isAtEnd()) {
            return false;
        }
        return $this->peek()->type == $type;
    }

    private function advance(): Token
    {
        if(!$this->isAtEnd()) {
            $this->current++;
        }
        return $this->previous();
    }

    private function isAtEnd(): bool
    {
        return $this->peek()->type == TokenType::EOF;
    }

    private function peek(): Token
    {
        return $this->tokens[$this->current];
    }

    private function previous(): Token
    {
        return $this->tokens[$this->current - 1];
    }

    private function error(Token $token, string $message): LoxParseError
    {
        Lox::errorAtToken($token, $message);
        return new LoxParseError();
    }

    private function synchronize(): void
    {
        $this->advance();

        while(!$this->isAtEnd()) {
            if($this->previous()->type == TokenType::SEMICOLON) {
                return;
            }

            switch($this->peek()->type) {
                case TokenType::CLASS_TOK:
                case TokenType::FUN:
                case TokenType::VAR:
                case TokenType::FOR:
                case TokenType::IF:
                case TokenType::WHILE:
                case TokenType::PRINT:
                case TokenType::RETURN:
                    return;
            }

            $this->advance();
        }
    }
}
