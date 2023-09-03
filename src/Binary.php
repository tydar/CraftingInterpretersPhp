<?php

namespace Lox;
class Binary extends Expr {
    public Expr $left;
    public Token $operator;
    public Expr $right;
    function __construct(Expr $left, Token $operator, Expr $right) {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }
}
