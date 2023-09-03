<?php

namespace Lox;

use Lox\Scanner;

class Lox
{
    public static bool $hadError = false;
    public static bool $hadRuntimeError = false;

    public static function main(array $args): void
    {
        if(count($args) > 1) {
            echo "Usage: php plox.php [script]" . PHP_EOL;
            exit(64);
        } elseif(count($args) == 1) {
            Lox::runFile($args[0]);
        } else {
            Lox::runPrompt();
        }
    }

    private static function runFile(string $path): void
    {
        $fileContent = file_get_contents($path);
        Lox::run($fileContent);
        if(Lox::$hadError) {
            exit(65);
        }

        if(Lox::$hadRuntimeError) {
            exit(70);
        }
    }

    private static function runPrompt(): void
    {
        while(true) {
            echo "> ";
            $line = fgets(STDIN);
            if($line === false) {
                break;
            }
            Lox::run($line);
            Lox::$hadError = false;
        }
    }

    private static function run(string $source): void
    {
        $scanner = new Scanner($source);
        $tokens = $scanner->scanTokens();

        $parser = new Parser($tokens);
        $expr = $parser->parse();

        $interpreter = new Interpreter();

        if(Lox::$hadError) {
            return;
        }

        $interpreter->interpret($expr);
    }

    public static function error(int $line, string $message): void
    {
        Lox::report($line, "", $message);
    }

    public static function runtimeError(LoxRuntimeError $error): void
    {
        $line = $error->token->line;
        fwrite(STDERR, $error->getMessage() . "\n[line $line]\n");
        Lox::$hadRuntimeError = true;
    }

    private static function report(int $line, string $where, string $message): void
    {
        fwrite(STDERR, "[line $line] Error $where : $message\n");
        Lox::$hadError = true;
    }

    public static function errorAtToken(Token $token, string $message): void
    {
        if($token->type == TokenType::EOF) {
            Lox::report($token->line, " at end", $message);
        } else {
            Lox::report($token->line, " at '" . $token->lexeme . "'", $message);
        }
    }
}
