<?php

namespace Lox;
class LiteralAst extends Expr {
    public Literal $value;
    function __construct(Literal $value) {
        $this->value = $value;
    }
}
