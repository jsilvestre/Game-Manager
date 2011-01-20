<?php

/*
 * Start sample configuration.
 */
$information = array(
	'meta' => array(
		'title' => 'Le titre',
		'charset' => 'utf-8',
		'author' => 'Spoutnik'
	),
	'misc' => array(
		'version' => '0.0.0'
	)
);
$css = array(
	'src' => 'game/ressources/styles/',
	'files' => array('main.css','menu.css')
);
$javascript = array(
	'src' => 'game/ressources/styles/',
	'files' => array('jquery-1.4.2.min.js')
);
$interface = array(
	array('id' => 'detection', 'target' => array('action'=>'sample','method'=>'index','params'=>null)),
	array('id' => 'infos_perso', 'target' => array('action'=>'sample','method'=>'index','params'=>null)),
	array('id' => 'principal', 'target' => array('action'=>'sample','method'=>'index','params'=>null)),
	array('id' => 'menu', 'target' => array('action'=>'sample','method'=>'index','params'=>null)),
	array('id' => 'deplacement', 'target' => array('action'=>'sample','method'=>'index','params'=>null)),
	array('id' => 'meteo', 'target' => array('action'=>'sample','method'=>'index','params'=>null))
);
$sample = array(
	'id' => 'sample',
	'information' => $information,
	'css' => $css,
	'js' => $javascript,
	'interface' => $interface,
	'default' => true
);
/*
 * End sample configuration.
 */

$application = array(
	'sample' => $sample
);
?>