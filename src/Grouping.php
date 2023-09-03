<?php

namespace Lox;
class Grouping extends Expr {
    public Expr $expression;
    function __construct(Expr $expression) {
        $this->expression = $expression;
    }
}
