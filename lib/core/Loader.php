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
 * @todo refactoring this class
 */

class Loader extends Library {
	
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
	 * Load an element to the application.
	 * It actually DOES the loading whereas the Library::load() method does basically nothing but use this method. 
	 * @param $type the type of the element to load (@see constants)
	 * @param $name
	 * @param GameManager $application the application instance
	 * @uses Loader::T_LIBRARY
	 * @uses Loader::T_CONFIG
	 * @uses Loader::T_ACTION
	 */
	function load($type,$name,GameManager $app) {
		
		if(is_array($name)) {
			foreach($name as $unit)
				$this->load($type,$unit,$app);
		}	
		else {
			$loadedObject = null;
			
			switch($type) {				
				case self::T_CONFIG :
					$loadedObject = $this->loadConfig($type,$name);
					break;
				case self::T_LIBRARY:
				case self::T_ACTION:
					$loadedObject = $this->loadClass($type,$name,$app);
					break;
			}
			// two events or just one ?
			if(!is_null($loadedObject)) {
				$this->getDispatcher()->notify(new sfEvent($loadedObject,'loader.object_loaded'));
				$this->getDispatcher()->notify(new sfEvent($loadedObject,'loader.'.$type.'.'.$name.'_loaded'));
			}
		}
	}
	
	private function loadClass($type,$name,GameManager $app) {
		
		$indexName = strtolower($name);
		
		$refl = new ReflectionClass($name); // the include is made by autoloading
		
		$instance = $refl->newInstance($app);
		
		$this->getContainer($type)->offsetSet($indexName,$instance);		
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
					
		$this->getContainer($type)->offsetSet($name, ${$name});
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
		$path = sfFinder::type('file')->name($className.'.php')->in($folder);
		if(count($path)>0 && file_exists($path[0])) {
			require_once($path[0]);
			
			if(!class_exists($className) && !interface_exists($className))
				throw new ClassNotFoundEx($className,$path[0]);
		}
	}
	
}