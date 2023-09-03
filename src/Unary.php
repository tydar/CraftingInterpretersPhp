<?php

namespace Lox;
class Unary extends Expr {
    public Token $operator;
    public Expr $right;
    function __construct(Token $operator, Expr $right) {
        $this->operator = $operator;
        $this->right = $right;
    }
}
