<?php

namespace Lox;

class Interpreter implements ExprVisitor {
    public function interpret(Expr $expression): void
    {
        try {
            $value = $this->evaluate($expression);
            $output = $this->stringify($value) . PHP_EOL;
            echo $output;
        } catch(LoxRuntimeError $e)  {
            Lox::runtimeError($e);
        }
    }

    public function visitLiteralAstExpr(LiteralAst $expr): mixed
    {
        return $expr->value->value;
    }

    public function visitGroupingExpr(Grouping $expr): mixed
    {
        return $this->evaluate($expr->expression);
    }

    public function visitUnaryExpr(Unary $expr): mixed
    {
        $right = $this->evaluate($expr->right);

        switch($expr->operator->type) {
            case TokenType::MINUS:
                $this->checkNumberOperand($expr->operator, $right);
                return -$right;
            case TokenType::BANG:
                return !$this->isTruthy($right);
        }

        return null;
    }

    public function visitBinaryExpr(Binary $expr): mixed
    {
        $left = $this->evaluate($expr->left);
        $right = $this->evaluate($expr->right);

        switch($expr->operator->type) {
            case TokenType::GREATER:
                $this->checkNumberOperands($expr->operator, $left, $right);
                return $left > $right;
            case TokenType::GREATER_EQUAL:
                $this->checkNumberOperands($expr->operator, $left, $right);
                return $left >= $right;
            case TokenType::LESS:
                $this->checkNumberOperands($expr->operator, $left, $right);
                return $left < $right;
            case TokenType::LESS_EQUAL:
                $this->checkNumberOperands($expr->operator, $left, $right);
                return $left <= $right;
            case TokenType::BANG_EQUAL: return !$this->isEqual($left, $right);
            case TokenType::EQUAL_EQUAL: return $this->isEqual($left, $right);
            case TokenType::MINUS:
                $this->checkNumberOperands($expr->operator, $left, $right);
                return $left - $right;
            case TokenType::PLUS:
                if(gettype($left) == 'double' && gettype($right) == 'double') {
                    return $left + $right;
                }

                if(gettype($left) == 'string' && gettype($right) == 'string') {
                    return $left . $right;
                }

                throw new LoxRuntimeError($expr->operator, "Operands must be two numbers or two strings. " . gettype($left) . " " . gettype($right));
            case TokenType::SLASH:
                $this->checkNumberOperands($expr->operator, $left, $right);
                return (double)$left / (double)$right;
            case TokenType::STAR:
                $this->checkNumberOperands($expr->operator, $left, $right);
                return $left * $right;
        }

        return null;
    }

    private function evaluate(Expr $expr): mixed
    {
        return $expr->accept($this);
    }

    private function isTruthy(mixed $val): bool
    {
        if(is_null($val)) return false;
        if(gettype($val) == 'boolean') return (bool)$val;
        return true;
    }

    private function isEqual(mixed $a, mixed $b): bool
    {
        return $a === $b;
    }

    private function checkNumberOperand(Token $operator, mixed $operand) : void
    {
        if(gettype($operand) == 'double') return;
        throw new LoxRuntimeError($operator, "Operand must be a number.");
    }

    private function checkNumberOperands(Token $operator, mixed $left, mixed $right) : void
    {
        if(gettype($left) == 'double' && gettype($right) == 'double') return;
        throw new LoxRuntimeError($operator, "Operands must be numbers.");
    }

    private function stringify(mixed $value) : string
    {
        if(is_null($value)) return 'nil';

        if(gettype($value) == 'double') {
            $text = strval($value);
            if(str_ends_with($text, '.0')) {
                $text = substr($text, 0, strlen($text) - 2);
            }
            return $text;
        }

        if(gettype($value) == 'boolean') {
            return $value ? 'true' : 'false';
        }

        if(gettype($value) == 'string') {
            return $value;
        }

        //should be unreachable
        return $value->__toString();
    }
}