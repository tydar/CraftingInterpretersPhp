<?php

// Generated by generate_ast.php on 03/Sep/2023

namespace Lox;

class Unary extends Expr
{
    public Token $operator;
    public Expr $right;
    public function __construct(Token $operator, Expr $right)
    {
        $this->operator = $operator;
        $this->right = $right;
    }

    public function accept(ExprVisitor $visitor): mixed
    {
        return $visitor->visitUnaryExpr($this);
    }
}
