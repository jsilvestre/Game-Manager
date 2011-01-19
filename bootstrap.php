<?php

include_once __DIR__.'/src/vendor/symfony/UniversalClassLoader.php';

$loader = new Symfony\Component\HttpFoundation\UniversalClassLoader();

$loader->registerNamespaces(array(
	'GameManager\\Core'	=> __DIR__.'/src',
	'Game'				=> __DIR__,
	'GameManager\\Test'	=> __DIR__.'/tests'
));

$loader->registerPrefixes(array(
    'sf'	=> __DIR__.'/src/vendor/symfony',
));

$loader->register();