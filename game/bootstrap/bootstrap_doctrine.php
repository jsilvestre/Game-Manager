<?php

// bootstrap_doctrine.php

/**
 * Bootstrap Doctrine.php, register autoloader specify
 * configuration attributes and load models.
 */

require_once(GameManager::getPath().'lib/external/Doctrine.php');

spl_autoload_register(array('Doctrine', 'autoload'));

$manager = Doctrine_Manager::getInstance();

$baseConf = GameManager::getInstance()->library('configuration')->get('base');

$dsn = $baseConf['database']['type'].':dbname='.$baseConf['database']['dbname'].';host='.$baseConf['database']['hostname'];
$user = $baseConf['database']['login'];
$password = $baseConf['database']['password'];

$dbh = new PDO($dsn, $user, $password);
$conn = Doctrine_Manager::connection($dbh);

$conn->execute('SHOW TABLES');
$conn->setCharset('utf8');

$conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
$conn->setAttribute(Doctrine_Core::ATTR_HYDRATE_OVERWRITE, false); 
$conn->setAttribute(Doctrine_Core::ATTR_CASCADE_SAVES, false);
 
$manager->setAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT, '%s_index'); 
$manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
$manager->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);

spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));

Doctrine_Core::loadModels('game/model/models');