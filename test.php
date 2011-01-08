<?php

include('lib/framework/core/Singleton.php');
include('lib/framework/Core.php');
require_once('game/bootstrap/bootstrap_doctrine.php');

//echo Doctrine_Core::getPath();
// database2files
//Doctrine_Core::generateModelsFromDb('models', array('doctrine'), array('generateTableClasses' => true));


/*$conn->export->createTable('test', array('name' => array('type' => 'string')));
$conn->execute('INSERT INTO test (name) VALUES (?)', array('jwage'));

$stmt = $conn->prepare('SELECT * FROM test');
$stmt->execute();
$results = $stmt->fetchAll();
print_r($results);*/

//$user = new Joueur();

//print_r($user->id_joueur);

//$conn->dropDatabase();
//$conn->createDatabase();

// Persistance des classes PHP vers la BDD
//Doctrine_Core::export('models');

// Syntaxe alternative :
//$db->export->export('models');


//$user = new Joueur();
//$conn->dropDatabase();
//$conn->createDatabase();

//Doctrine_Core::generateModelsFromDb('game/model/models', array('doctrine'), array('generateTableClasses' => true));
//Doctrine_Core::createTablesFromModels('game/model/models');
//Doctrine_Core::generateSqlFromArray(array('Map', 'Joueur'));

/*$map = new Carte();

$map->nom = "Monde";
$map->description = "la carte du monde";
$map->save();

$user = new Joueur();

$user->pseudo = "Spoutnik";
$user->password = "test";
$user->id_carte = 1;
$user->x = 1;
$user->y = 1;
$user->expertise = 1;
$user->connaissance = 1;
$user->agilite = 1;
$user->pend = 1;
$user->pv = 1;
$user->pe = 1;
$user->money = 1;
$user->last_connection = 1;
$user->date_inscription = 1;


$user->save();*/

echo "test.php";