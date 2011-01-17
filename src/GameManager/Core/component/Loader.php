<?php
/**
 * Loader class
 * 
 * Execute all the loading the application needs. It doesn't include the files : the symfony universal autoloader does it well.
 *
 * @package     GameManager
 * @subpackage  Loader
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @todo this class needs a strong documentation :)
 */

namespace GameManager\Core\Component;

use \GameManager\Core\Application;

class Loader {
	
	/**
	 * The type of element to load : library
	 * @staticvar string
	 */
	const T_LIBRARY = 'library';

	/**
	 * The type of element to load : action
	 * @staticvar string
	 */
	const T_ACTION = 'action';

	/**
	 * The type of element to load : config
	 * @staticvar string
	 */
	const T_CONFIG = 'config';
	
	/**
	 * The application object instance
	 * @var GameManager
	 * @access protected
	 */	
	protected $application = null;
	
	/**
	 * Load an element to the application.
	 * @param $type the type of the element to load (@see constants)
	 * @param $name
	 * @uses Loader::T_LIBRARY
	 * @uses Loader::T_CONFIG
	 * @uses Loader::T_ACTION
	 * @dispatches loader.object_loaded event when a something is loaded
	 */
	function load($type,$name) {
		
		if(is_array($name)) {
			foreach($name as $unit)
				$this->load($type,$unit);
		}	
		else {
			$loadedObject = null;
			
			switch($type) {				
				case self::T_CONFIG :
					$loadedObject = $this->loadConfig($type,$name);
					break;
				case self::T_LIBRARY:
				case self::T_ACTION:
					$loadedObject = $this->loadClass($type,$name,'GameManager\Core\Library\\');
					break;
			}

			if(!is_null($loadedObject)) {
				$this->getApplication()->getEventDispatcher()->notify(new sfEvent($loadedObject,'loader.object_loaded'));
			}
		}
	}
	
	private function loadClass($type,$name,$ns) {
		
		$indexName = strtolower($name);	
		
		$refl = new \ReflectionClass($ns.$name); // the include is made by autoloading
		
		$instance = $refl->newInstance($this->getApplication());
		
		$this->getApplication()->getContainer($type)->offsetSet($indexName,$instance);		
	}
	
	private function loadConfig($type,$name) {
		
		$folder = \Core\GameManager::getPath()."game/config/";
		
		$path = $folder.$name.'.php';

		Loader::checkFileValidity($name,$folder);
		
		require($path);
		
		if(!isset(${$name}))
			throw new \ConfigVarNotFoundEx($name);
			
		if(!is_array(${$name}))
			throw new \WrongDataTypeEx($name,gettype(${$name}),"Array");
					
		$this->getApplication()->getContainer($type)->offsetSet($name, ${$name});
	}

	/**
	 * Gets the application object instance
	 * @return GameManager
 	 * @access protected
	 */
	protected function getApplication() {
		if(is_null($this->application))
			throw new RuntimeException('Loader::getApplication. An application object must be set to the Loader.');
			
		return $this->application;
	}
	
	/**
	 * Sets the application object instance
	 * @param GameManager $application
	 */
	public function setApplication(Application $application) {
		$this->application = $application;
	}
	
	
	/**
	 * Returns the absolute path of the application
	 * @return string
	 * @static
	 */
	public static function getPath() {
		return realpath(dirname(__FILE__) . '/../..').'/';
	}	
}