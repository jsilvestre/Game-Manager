<?php
include('lib/core/Library.php');
include('lib/core/Loader.php');
include('lib/core/Collection.php');
include('lib/external/vendor/sfFinder.php');

spl_autoload_register(array('Loader','autoloadException'));
spl_autoload_register(array('Loader','autoloadExternal'));
spl_autoload_register(array('Loader','autoloadLibrary'));

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
 throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");


try {

	$dispatcher = new sfEventDispatcher();

	$gm = new GameManager($dispatcher);
	
	$gm->init();
	
	$gm->load('library','Router');
	
	
}
catch(Exception $e) {
	echo $e.'<br /><br />';
	echo $e->getMessage().'<br />'.$e->getLine().'<br />'.$e->getFile().'<br />';
}
catch(ErrorException $e) {
	echo $e->getMessage();
}