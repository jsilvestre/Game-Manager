<?php

include "bootstrap.php";

use \GameManager\Core\Component as Comp;
use GameManager\Core\Application;

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
 throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

session_start();

try {
	// you can specify a default module here. Configuration default module will be ignored. 
	$default_module = null;
	
	$dispatcher = new sfEventDispatcher();
	$request = new Comp\Request($default_module);
	$response = new Comp\Response();	
	$loader = new Comp\Loader();

	$gm = new \GameManager\Core\Application($dispatcher,$request,$response,$loader);
	
	$gm->init();
	
	$gm->getContainer(Comp\Loader::T_LIBRARY)->offsetGet('router')->setSourceArray($_GET);	
	$gm->getContainer(Comp\Loader::T_LIBRARY)->offsetGet('router')->processInformation();
	
	$gm->loadApplicationConfiguration(__DIR__.'/'.Application::P_CONFIG);

	$gm->getContainer(Comp\Loader::T_LIBRARY)->offsetGet('router')->processRouting();
	
	$gm->getContainer(Comp\Loader::T_LIBRARY)->offsetGet('hud')->create();
	
	$gm->getContainer(Comp\Loader::T_LIBRARY)->offsetGet('hud')->setRoutesToElements();
	
	$gm->getContainer(Comp\Loader::T_LIBRARY)->offsetGet('hud')->render();
	
	$gm->uninit();
	
	echo $gm->getResponse()->render();
}
catch(Exception $e) {
	echo get_class($e).': '.$e->getMessage().'<br />'.$e->getLine().'<br />'.$e->getFile().'<br />'.str_replace('#','<br />',$e->getTraceAsString());
}
catch(ErrorException $e) {
	echo $e->getMessage();
}

session_write_close();