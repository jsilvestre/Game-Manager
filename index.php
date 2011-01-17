<?php

include "bootstrap.php";

use \GameManager\Core\Component as Comp;

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
 throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

try {
	
	$dispatcher = new sfEventDispatcher();
	$request = new Comp\Request();
	$response = new Comp\Response();
	$loader = new Comp\Loader();

	$gm = new \GameManager\Core\Application($dispatcher,$request,$response,$loader);
	
	$gm->init();
	
	$gm->getContainer(Comp\Loader::T_LIBRARY)->offsetGet('router')->setSourceArray($_GET);
	$gm->getContainer(Comp\Loader::T_LIBRARY)->offsetGet('router')->processRouting($request);

}
catch(Exception $e) {
	echo $e.'<br /><br />';
	echo $e->getMessage().'<br />'.$e->getLine().'<br />'.$e->getFile().'<br />';
}
catch(ErrorException $e) {
	echo $e->getMessage();
}