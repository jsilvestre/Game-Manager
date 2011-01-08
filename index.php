<?php

error_reporting(E_ALL ^ E_STRICT);

/**
* Is the application running in you developing environment (or in your production environment) ?
*/
define('ENV_DEVELOPER',TRUE);

include('lib/framework/core/Singleton.php');
include('lib/framework/Core.php');

include('lib/external/Doctrine.php');
include('lib/external/vendor/sfFinder.php');

spl_autoload_register(array('GameManager','autoloadException'));
spl_autoload_register(array('GameManager','autoloadLibrary'));

session_start();

try {
	
	$gameManager = GameManager::getInstance()->init();
	
	require_once('game/bootstrap/bootstrap.php');
	require_once('game/bootstrap/bootstrap_doctrine.php');

	// login attempt detection
	$tryToLogin = true; // isset($_POST['login_attempt'])
	$pleaseUnlog = false;
	$login = true;
	if($tryToLogin)
		$login = $gameManager->auth(); // login will go false if an error occured	
				
	if($gameManager->library('authentificator')->isUserLogged() && $pleaseUnlog)
		$gameManager->library('session')->unsetData('user');

	if($login && $gameManager->library('authentificator')->isUserLogged()) {
		/* execute hooks here */		
		echo $gameManager->play();
	}
	else {
		$baseConf = $gameManager->library('configuration')->get('base');
		header('Location: '.$baseConf['url_login_fail']);
	}
}
catch(GameManagerEx $e) {
	$e->display();
}
catch(Exception $e) {
	echo "Critical error: ".$e->getMessage().'. Please contact an administrator as soon as possible.';	
}

session_write_close();