<?php

// Generated by generate_ast.php on 03/Sep/2023

namespace Lox;

class Grouping extends Expr
{
    public Expr $expression;
    public function __construct(Expr $expression)
    {
        $this->expression = $expression;
    }

    public function accept(ExprVisitor $visitor): mixed
    {
        return $visitor->visitGroupingExpr($this);
    }
}
