<?php
/**
 * Loader class
 * 
 * Execute all the loading the application needs. Autoloading, configuration array loading, etc.
 *
 * @package     GameManager
 * @subpackage  Loader
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @todo refactoring this class : using the universal autoload from Symfony framework
 */

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
					$loadedObject = $this->loadClass($type,$name);
					break;
			}

			if(!is_null($loadedObject)) {
				$this->getApplication()->getEventDispatcher()->notify(new sfEvent($loadedObject,'loader.object_loaded'));
			}
		}
	}
	
	private function loadClass($type,$name) {
		
		$indexName = strtolower($name);
		
		$refl = new ReflectionClass($name); // the include is made by autoloading
		
		$instance = $refl->newInstance($this->getApplication());
		
		$this->getApplication()->getContainer($type)->offsetSet($indexName,$instance);		
	}
	
	private function loadConfig($type,$name) {
		
		$folder = GameManager::getPath()."game/config/";
		
		$path = $folder.$name.'.php';

		Loader::checkFileValidity($name,$folder);
		
		require($path);
		
		if(!isset(${$name}))
			throw new ConfigVarNotFoundEx($name);
			
		if(!is_array(${$name}))
			throw new WrongDataTypeEx($name,gettype(${$name}),"Array");
					
		$this->getApplication()->getContainer($type)->offsetSet($name, ${$name});
	}
	
	/**
	* Test if the file can be load. Throw a FileNotFoundEx if the test fail.
	*
	* @static
	*
	* @param string $filename
	* @param string $folder
	*/
	private static function checkFileValidity($filename,$folder) {
		
		$path = $folder.$filename.".php";
		
		if(!file_exists($path))
			throw new FileNotFoundEx($path);			
	}
	
	/**
	 * Exception autoloading
	 * @static
	 * @param string $className
	 */
	public static function autoloadException($className) {
		$folder = 'lib/core/exceptions/';
		
		self::executeAutoLoad($className,$folder);
	}

	/**
	 * Library autoloading
	 * @static
	 * @param string $className
	 */
	public static function autoloadLibrary($className) {
		$folder = 'lib/core/';
				
		self::executeAutoLoad($className,$folder);
	}

	/**
	 * External autoloading
	 * @static
	 * @param string $className
	 */
	public static function autoloadExternal($className) {
		$folder = 'lib/external/';
				
		self::executeAutoLoad($className,$folder);
	}
	
	/**
	 * Realize the requested class loading
	 * @static
	 * @param string $className
	 * @param string $folder
	 * @access private
	 * @todo making the function search in the extension folder to look if there is a personal version of the file
	 */
	private static function executeAutoload($className,$folder) {
		
		// we use the sfFinder class to find out where the file is
		$path = sfFinder::type('file')->name($className.'.php')->in(self::getPath().$folder);
		if(count($path)>0 && file_exists($path[0])) {
			require_once($path[0]);
			
			if(!class_exists($className) && !interface_exists($className))
				throw new ClassNotFoundEx($className,$path[0]);
		}
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
	public function setApplication(GameManager $application) {
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