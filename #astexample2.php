<?php
require 'vendor/autoload.php';

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

/*$code = <<<'CODE'
<?php

function test($foo)
{
    var_dump($foo);
}
CODE;*/


$archivo = file_get_contents('archivo.php'); //Transmite un fichero entero a una cadena

/*
$re = '/(?=(\((?:[^()]++|(?1))*\)))/'; 
$str = 'a(bcdefghijkl(mno))p)q((('; 
preg_match_all($re, $str, $m); 
print_r($m[1]); // => Array ( [0] => (bcdefghijkl(mno)p) [1] => (mno) ) */

if(preg_match_all("#(?=(\[>(?:[^(<\])]+)*<\]))#",$archivo,$matches)) {
//if (preg_match_all("/(?=(\((?:[^()]++|(?1))*\)))/",$archivo,$matches)) {
    $num_matches = count($matches[1]);
    echo "Matches: " . $num_matches . "\n";
    for ($i = 0; $i < $num_matches; $i++) {
        echo "Párrafo " . ($i+1) . ": " . $matches[1][$i] . "\n";
    }
}

//preg_match('~(\[>([^<]*))*<\]~i', $archivo, $match);
//print_r($match); 

$code = <<<'CODE'
<?php
echo $match[1];
CODE;



		
$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$dumper = new NodeDumper;
//echo $dumper->dump($ast) . "\n"; 
?>