<?php

/**
 * This is the core class of the application. It subclasses Singleton
 *
 * @package     GameManager
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class GameManager extends Singleton {
	
    /**
     * Framework version
     */
    const VERSION				= '0.0.1';
	
    /**
     * Array containing the instances of the loaded librairies
     */	
	private $_libraries = array();
	
	/**
	* Array containing the differents configuration arrays
	*/
	private $_configurations = array();
	
	/**
	 * Initialize the framework.
	 *
	 * @return Core
	 */
	public function init() {
		$this->load = new Loader(); // the very first thing the application should do is creating its loader.

		$this->load->library(array('Configuration','Security','Session','Router','Hud','Authentificator')); // then loading the core libraries
		
		$this->load->configuration('base'); // then loading the application configuration file
		
		// then we process autoloading
		$baseConf = $this->library('configuration')->get('base');
		$this->load->library($baseConf["autoload"]["library"]);
		$this->load->configuration($baseConf["autoload"]["configuration"]);
		$this->load->helper($baseConf["autoload"]["helper"]);
		
		return $this;
	}
	
	public function play() {

		$this->library('security')->protectGlobal();
		$this->library('router')->processRouting();

		$this->library('hud')->create('hud.xml');
		$this->library('hud')->setRoutesToElements($this->library('router')->getRoutes());

		$this->load->helper('util');

		return $this->library('hud')->render();		
	}
	
	public function auth() {

		/*if(!isset($_POST[$this->getConf(self::AUTH_LOGIN_FIELD)]) || !isset($_POST[$this->getConf(self::AUTH_PASSWORD_FIELD)]))
			return false;*/
				
		/*$login = $_POST[$this->getConf(self::AUTH_LOGIN_FIELD)];
		$password = $_POST[$this->getConf(self::AUT_PASSWORD_FIELD)];*/
		
		$login = "Spoutnik";
		$password = "test";

		//if(!$this->library('authentificator')->isUserLogged())
			$login = $this->library('authentificator')->auth($login,$password);
			
		return $login;
	}
	
	/**
	* Add a library instance to the library array of the application
	* Throw WrongDataTypeEx
	*
	* @param $libName
	* @param $libInstance
	*/
	public function addLibrary($libName,$libInstance) {
		if(!is_string($libName))
			throw new WrongDataTypeEx("libName",gettype($libName),"string");
		
		if(!($libInstance instanceof Library))
			throw new WrongDataTypeEx("libInstance",gettype($libInstance),"Library");
		
		$this->_libraries[$libName] = $libInstance;		
	}

	/**
	* Add a configuration file to the configuration array of the application
	* Throw WrongDataTypeEx
	*
	* @param $configName
	* @param $configArray
	* @deprecated
	*/	
	/*public function addConfiguration($configName,$configArray) {
		if(!is_string($configName))
			throw new WrongDataTypeEx("configName",gettype($configName),"string");
		
		if(!is_array($configArray))
			throw new WrongDataTypeEx("configArray",gettype($configArray),"array");
			
		$this->_configurations[$configName] = $configArray;		
	}*/
		
	/**
	 * Get back the instance of the requested library. 
	 * Throw a LibNotLoadedEx if the library class has not been loaded yet.
	 * 
	 * @param $libName
	 * @return Library
	 */	
	function library($libName) {
		if(!array_key_exists($libName,$this->_libraries))
			throw new LibNotLoadedEx($libName);
			
		return $this->_libraries[$libName];
	}

	/**
	 * Get back the array of the requested config file. 
	 * Throw a ConfigNotLoadedEx if the configuration class has not been loaded yet.
	 * 
	 * @param $configName
	 * @return array
	 * @deprecated
	 */	
	/*function configuration($configName,$loadIfNot=false) {
		if(!array_key_exists($configName,$this->_configurations))
			throw new ConfigNotLoadedEx($configName);
			
		return $this->_configurations[$configName];		
	}*/
	
	/**
	 * Return the absolute path to the application's main folder
	 * 
	 * @static
	 *
	 * @return string
	 */
	public static function getPath() {
		return realpath(dirname(__FILE__) . '/../..').'/';
	}	

	/**
	 * Exception autoloading
	 * 
	 * @static
	 *
	 * @param string $className
	 */
	public static function autoloadException($className) {
		$folder = 'lib/exceptions/';
		
		self::executeAutoLoad($className,$folder);
	}

	/**
	 * Library autoloading
	 * 
	 * @static
	 *
	 * @param string $className
	 */
	public static function autoloadLibrary($className) {
		$folder = 'lib/framework/';
				
		self::executeAutoLoad($className,$folder);
	}
	
	/**
	 * Realize the requested class loading
	 *
	 * @static
	 * 
	 * @param string $className
	 * @param string $folder
	 */
	private static function executeAutoload($className,$folder) {

		//$path = self::getPath().$folder.$className.'.php';
		
		// we use the sfFinder class to find out where the file is
		$path = sfFinder::type('file')->name($className.'.php')->in($folder);
		if(count($path)>0 && file_exists($path[0])) {
			require_once($path[0]);
			
			if(!class_exists($className) && !interface_exists($className))
				throw new ClassNotFoundEx($className,$path[0]);
		}
	}	
}