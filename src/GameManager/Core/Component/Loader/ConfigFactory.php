<?php
/**
 * Configuration Factory.
 * Load the configuration arrays.
 *
 * @package     GameManager
 * @subpackage  Loader
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

namespace GameManager\Core\Component\Loader;

use GameManager\Core\Application;
use GameManager\Core\Component\Loader;

class ConfigFactory extends Factory {

	/**
	 * @inheritdoc
	 * @throws RuntimeException if the @see Application::N_CONFIG_APP is tried to be loaded.
	 * @throws \GameManager\Core\Exception\FileNotFoundEx if the config file is not found.
	 * @throws \GameManager\Core\Exception\ConfigVarNotFoundEx if there is not the config var expected in the file 
	 * @throws \UnexpectedValueException if the config var exists but it is not an array
	 */
	public function process($name) {
		
		if($name == Application::N_CONFIG_APP)
			throw new RuntimeException("You can't load the ".Application::N_CONFIG_APP." config file : the application does it itself properly.");
		
		$folder = Application::getPath()."/game/configuration";
		
		$path = $folder.'/'.$name.'.php';

		if(!file_exists($path))
			throw new \GameManager\Core\Exception\FileNotFoundEx();
		
		require($path);
		
		if(!isset(${$name}))
			throw new \GameManager\Core\Exception\ConfigVarNotFoundEx($name);
			
		if(!is_array(${$name}))
			throw new \UnexpectedValueException("The var ".$name." has a wrong type. Given ".gettype(${$name}).", expected array.");
									
		return array(
					'index'	=> $name,
					'value'	=> ${$name}
				);			
	}
	
	
	
}