<?php
require 'vendor/autoload.php';

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Mul;
//use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;

use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;


$archivo = file_get_contents('archivo.php'); //Transmite un fichero entero a una cadena

$archivo = '[» [« $x=\'Hola\'; »] «]';

$exp_reg='/\[»((?:[^\[\]]|\[(?![«»])|(?<![«»])\])*)«\]|\[«((?:[^\[\]]|\[(?![«»])|(?<![«»])\])*)»\]/';
	
function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
	$e=$archivo;
	do{
			$c=0;
			
			$e= preg_replace_callback($exp_reg, function ($matches){
						if(startsWith($matches[0],'"')){
							return $matches[0];
						} else if (startsWith($matches[0],'[«')) {
							$code = substr($matches[0], 2, -2);
							echo "Parsing ... ". $code;
							global $parser, $c, $ast;
							$ast = $parser->parse('<?php '. $code .'?>');
							echo $ast;
							$c++;
							return ast2php($ast);
							
						} else if (startsWith($matches[0],'[»')) {
							$code = substr($matches[0], 2, -2);
							echo "Evaluating ... ". $code;
							$prettyPrinter = new PrettyPrinter\Standard;
							$evaledAST = eval($code); 
							
							if ($evaledAST instanceof Expr) { 
								return $prettyPrinter->prettyPrintExpr($evaledAST);  //unparser es esto
							} else {
								//echo $prettyPrinter->prettyPrint($evaledAST);
							}
						}
				}, $e);
			echo $e;
	}while($c>0);
	
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}






function ast2php($ast) : string {
	
       $code=dumpRecursive($ast,0);
       return 'return '.substr ($code ,0, strlen($code)-1).';'; //con el substr le elimino un parentesis que cierra que queda de sobra
    }

 function dumpRecursive($node,$contador) {
	   
		
        if ($node instanceof PhpParser\Node) {
            $tipo = $node->getType();
			$salida= 'new PhpParser\Node\\'.str_replace('_','\\',$tipo);
			
			//Si es de tipo Scalar_String la variable le agrego guion bajo poque la clase en el parser esta definida con guion bajo al final
			if($tipo === 'Scalar_String'){
				$salida .='_';
			}	
			
			 //Cuando la funcion o expresion tiene mas de un parametro lo separo con coma
			if($contador>0){
				$salida =','.$salida;
				$contador=0;
			}

			$salida .='(';
            foreach ($node->getSubNodeNames() as $key) {
                			
                $value = $node->$key;
                if (null === $value) {
                    $salida .= 'null';
                } elseif (false === $value) {
                    $salida .= 'false';
                } elseif (true === $value) {
                    $salida .= 'true';
                } elseif (is_scalar($value)) {
					if(is_string($value)){
						$salida .= "\"";
						$salida .= $value;
						$salida .= "\"";
					}else
						$salida .= $value;

                } else {
					
					$salida .= str_replace("\n", "\n    ", dumpRecursive($value,$contador++));
                }
            }

        } elseif (is_array($node)) {
			 $salida = '';

            foreach ($node as $key => $value) {
 
                if (null === $value) {
                    $salida .= 'null';
                } elseif (false === $value) {
                    $salida .= 'false';
                } elseif (true === $value) {
                    $salida .= 'true';
                } elseif (is_scalar($value)) {
					$salida .= $value;
					
                } else {
                    $salida .= str_replace("\n", "\n    ", dumpRecursive($value,$contador++));
					
                }
            }
        } elseif ($node instanceof Comment) {
            return $node->getReformattedText();
        } else {
            throw new \InvalidArgumentException('Can only dump nodes and arrays.');
        }

        return $salida . "\n)";
    }


/*$prettyPrinter = new PrettyPrinter\Standard;
$evaledAST = eval($e); 

if ($evaledAST instanceof Expr) { 
	echo $prettyPrinter->prettyPrintExpr($evaledAST);
} else {
	//echo $prettyPrinter->prettyPrint($evaledAST);
}*/

?>