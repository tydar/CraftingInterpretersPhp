<?php

namespace Lox;

class AstPrinter implements ExprVisitor {

    function print(Expr $expr) : string
    {
        return $expr->accept($this);
    }

    public function visitBinaryExpr(Binary $expr): mixed
    {
        return $this->parenthesize($expr->operator->lexeme, $expr->left, $expr->right);
    }

    public function visitGroupingExpr(Grouping $expr): mixed
    {
        return $this->parenthesize('group', $expr->expression);
    }

    public function visitLiteralAstExpr(LiteralAst $expr): mixed
    {
        if (is_null($expr->value)) return 'nil';
        return $expr->value->__toString();
    }

    public function visitUnaryExpr(Unary $expr): mixed
    {
        return $this->parenthesize($expr->operator->lexeme, $expr->right);
    }

    private function parenthesize(string $name, Expr ... $exprs) : string
    {
        $result = '('.$name;

        foreach($exprs as $expr) {
            $result .= ' ';
            $result .= $expr->accept($this);
        }
        $result .= ')';

        return $result;
    }
}