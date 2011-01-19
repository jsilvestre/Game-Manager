<?php

/*
 * Start Sample configuration
 */
$information = array(
				'meta'	=> array(
							'title'		=> 'Mon titre',
							'charset'	=> 'utf8',
							'author'	=> 'Spoutnik'
						),
				'misc'	=> array (
							'version' => '0.0.0'
						)
			);
			
$css = array(
		'src' => 'game/ressource/style/',
		'files' => array('main.css','menu.css')
	);
		
$javascript = array(
				'src' => 'game/ressource/script/',
				'files' => array('jquery-1.4.2.min.js')
			);
		
$interface = array(
				array('id' => 'detection', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'infos_perso', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'principal', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'menu', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'deplacement', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'meteo', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null))
			);
				
$sample = array(
				'id'			=> 'sample',
				'information'	=> $information,
				'css' 			=> $css,
				'js'			=> $javascript,
				'interface'		=> $interface,
				'default'		=> true
			);
			
/*
 * End Sample configuration
 */
			
/*
 * Start {module_name} configuration
 */
$information = array(
				'meta'	=> array(
							'title'		=> 'Mon titre de la partie admin',
							'charset'	=> 'utf8',
							'author'	=> 'Spoutnik'
						),
				'misc'	=> array (
							'version' => '0.0.0'
						)
			);
			
$css = array(
		'src' => 'game/ressource/style/',
		'files' => array('main.css','menu.css')
	);
		
$javascript = array(
				'src' => 'game/ressource/script/',
				'files' => array('jquery-1.4.2.min.js')
			);
		
$interface = array(
				array('id' => 'detection', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'infos_perso', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'principal', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'menu', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'deplacement', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null)),
				array('id' => 'meteo', 'target' => array('action' => 'sample', 'method' => 'index', 'params' => null))
			);
				
$module2 = array(
				'id'			=> 'module2',
				'information'	=> $information,
				'css' 			=> $css,
				'js'			=> $javascript,
				'interface'		=> $interface
			);
			
/*
 * End {module_name} configuration
 */
			
$application = array(
					'sample'	=> $sample,
					'module2'	=> $module2
				);