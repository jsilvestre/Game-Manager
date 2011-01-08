<?php

// title of your page
$base["title"] = "MySuperGame is Awesome";

// autoloading arrays
$base["autoload"]["configuration"] = array();
$base["autoload"]["library"] = array();
$base["autoload"]["helper"] = array();

// game version
$base["game_version"] = "0.0.0";

// when it's true, user will not be able to reload the main page of the game without being disconnected
$base["keep_from_reloading"] = FALSE;

// duration of a session in second
$base["session_limit"] = 1800; // 1800 correspond to half an hour

// database configuration
$base["database"] = array(
						'type'		=> 'mysql',
						'hostname'	=> 'localhost',
						'port'		=> '3306',
						'login'		=> 'root',
						'password'	=> 'root',
						'dbname'	=> 'destrukalips'			
					);

// define the CSS folder
$base["css_folder"] = 'game/ressources/css/';

// define the Javascript folder
$base["javascript_folder"] = 'game/ressources/javascript/';

// define the image folder
$base["image_folder"] = 'game/ressources/images/';

// the url the user will be redirected if the login attempt fails
$base['url_login_fail'] = '../';