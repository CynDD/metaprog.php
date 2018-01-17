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

//strrpos — Encuentra la posición de la última aparición de un substring en un string
//explode — Divide un string en varios string, devuelve un array de string
$indiceAbre=strrpos( $archivo ,'[>');
$indiceCierra=stripos( $archivo ,'<]');


$subcadena=substr($archivo ,$indiceAbre+2,$indiceCierra-($indiceAbre+2));
//echo 'Primero:'.$subcadena;

$primero = explode('[>'.$subcadena.'<]', $archivo);
echo $primero[0].$primero[1];

/*$porciones1 = explode('[>', $archivo);
echo $porciones1[0];
$porciones2 = explode('<]', $porciones1[1]);
//echo 'Segundo:'.$porciones1[0].$porciones2[1];*/

//echo $porciones[0];
//echo $porciones[1];
//echo $indiceAbre;
//echo $indiceCierra;

/*for($i=0;$i<strlen($archivo);$i++){ 
    echo $archivo[$i]; 
	echo($archivo[$i]==[);
	if($archivo[$i]=='[' and $archivo[$i+1]=='<'){
		array_push($position, $i);
		echo $i;
		
	}
}*/


/*if(preg_match_all("#(?=(\[>(?:[^(<\])]+)*<\]))#",$archivo,$matches)) {
//if (preg_match_all("/(?=(\((?:[^()]++|(?1))*\)))/",$archivo,$matches)) {
    $num_matches = count($matches[1]);
    echo "Matches: " . $num_matches . "\n";
    for ($i = 0; $i < $num_matches; $i++) {
        echo "Párrafo " . ($i+1) . ": " . $matches[1][$i] . "\n";
    }
}*/

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