<?php

$classes = [
    'Binary : Expr $left, Token $operator, Expr $right',
    'Grouping : Expr $expression',
    'LiteralAst : Literal $value',
    'Unary : Token $operator, Expr $right'
];

function defineAst(string $outputDir, string $baseName, array $types) : void
{ 
    $path = __DIR__."/".$outputDir."/".$baseName.".php";

    $contents = "<?php" . PHP_EOL . PHP_EOL . "namespace Lox;" . PHP_EOL;

    $contents .= "abstract class " . $baseName . " {" . PHP_EOL;
    $contents .= "}";

    file_put_contents($path, $contents);

    foreach($types as $type) {
        $className = trim(explode(":", $type)[0]);
        $fields = trim(explode(":", $type)[1]);
        defineType($outputDir, $baseName, $className, $fields);
    }
}

function defineType(string $outputDir, string $baseName, string $className, string $fieldList) : void
{
    $path = __DIR__."/".$outputDir."/".$className.".php"; 

    $contents = "<?php" . PHP_EOL . PHP_EOL . "namespace Lox;" . PHP_EOL;

    $contents .= "class " . $className . " extends " . $baseName . " {" . PHP_EOL;

    // fields
    $fields = explode(",", $fieldList);
    foreach($fields as $field) {
        $field = trim($field);
        $contents .= '    public ' . $field . ';' . PHP_EOL;
    }
    
    // __construct
    $contents .= "    function __construct(" . $fieldList . ") {" . PHP_EOL;
    foreach($fields as $field) {
        $field = trim($field);
        $name = substr(explode(" ", $field)[1], 1);
        $contents .= '        $this->'. $name. ' = $'. $name . ';' . PHP_EOL;
    }
    $contents .= '    }' . PHP_EOL;
    $contents .= '}' . PHP_EOL;

    file_put_contents($path, $contents);
}

defineAst('../src', 'Expr', $classes);