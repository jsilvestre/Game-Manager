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
 * @todo this class needs a strong documentation :)
 */

namespace GameManager\Core\Component\Loader;

use GameManager\Core\Application;
use GameManager\Core\Component\Loader;

class ConfigFactory {
		
	protected $application;
	
	function __construct(Application $app) {
		$this->application = $app;
	}
	
	public function process($name,$path) {
		
		$folder = Application::getPath()."game/config/";
		
		$path = $folder.$name.'.php';

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