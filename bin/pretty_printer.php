<?php

require __DIR__.'/../vendor/autoload.php';

use Lox\Binary;
use Lox\Unary;
use Lox\LiteralAst;
use Lox\Literal;
use Lox\Grouping;
use Lox\Token;
use Lox\TokenType;
use Lox\LiteralType;
use Lox\AstPrinter;

$expression = new Binary(
    new Unary(
        new Token(TokenType::MINUS, '-', null, 1),
        new LiteralAst(new Literal(LiteralType::NUMBER, 123))
    ),
    new Token(
        TokenType::STAR, '*', null, 1
    ),
    new Grouping(
        new LiteralAst(new Literal(LiteralType::NUMBER, 45.67))
    )
);

$printer = new AstPrinter();
$msg = $printer->print($expression) . PHP_EOL;
echo $msg;