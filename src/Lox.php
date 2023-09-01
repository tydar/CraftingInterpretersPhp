<?php

namespace Lox;

use Lox\Scanner;

class Lox {

    static bool $hadError = false;

    public static function main(array $args) : void
    {
        if(count($args) > 1) {
            echo "Usage: php plox.php [script]" . PHP_EOL;
            exit(64);
        } else if(count($args) == 1) {
            Lox::runFile($args[0]);
        } else {
            Lox::runPrompt();
        }
    }

    private static function runFile(string $path) : void
    {
        $fileContent = file_get_contents($path);
        Lox::run($fileContent);
        if(Lox::$hadError) {
            exit(65);
        }
    }

    private static function runPrompt() : void
    {
        while(true) {
            echo "> ";
            $line = fgets(STDIN);
            if($line === false) break;
            Lox::run($line);
            Lox::$hadError = false;
        }
    }

    private static function run(string $source) : void
    {
        $scanner = new Scanner($source);
        $tokens = $scanner->scanTokens();

        foreach($tokens as $token) {
            echo $token . PHP_EOL;
        }
    }

    static function error(int $line, string $message) : void
    {
        Lox::report($line, "", $message);
    }

    private static function report(int $line, string $where, string $message) : void
    {
        fwrite(STDERR, "[line $line] Error $where : $message");
        Lox::$hadError = true;
    }
}