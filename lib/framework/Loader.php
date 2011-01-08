<?php

/**
 * Loader class. It strongly depends of the class GameManager.
 *
 * @package     GameManager
 * @subpackage  Loader
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class Loader extends Library {

	/**
	* Load a library class or an library class' array.
	* Throw WrongDataTypeEx if the parameter $libraries is not a string or an array.
	*
	* @param $libraries
	* @param $libName
	*/	
	public function library($libraries,$libName=NULL) {
		
		if(is_array($libraries)) {
			foreach($libraries as $lib) {
				$this->library($lib);							
			}			
		}
		else {
		
			if(!is_string($libraries))
				throw new WrongDataTypeEx("libraries",gettype($libraries),"string");
				
			if(is_null($libName))
				$libName = $libraries;
				
			$libName = strtolower($libName);
			GameManager::getInstance()->addLibrary($libName,$this->loadLibrary($libraries));
		}	
	}

	/**
	* Load a action class or an action class' array.
	* Throw WrongDataTypeEx if the parameter $actions is not a string or an array.
	*
	* @param $actions
	*/	
	public function action($actions) {
		
		if(is_array($actions)) {
			foreach($actions as $action) {
				$this->action($action);							
			}			
		}
		else {
		
			if(!is_string($actions))
				throw new WrongDataTypeEx("actions",gettype($actions),"string");
	
			$actionName = strtolower($actions);
			GameManager::getInstance()->library('hud')->addAction($actionName,$this->loadAction($actions));
		}	
	}

	/**
	* Load a configuration array or an configuration array's array.
	* Throw WrongDataTypeEx if the parameter $configurations is not a string or an array.
	*
	* @param $configurations
	* @param $configName
	*/	
	function configuration($configurations,$configName=NULL) {

		if(is_array($configurations)) {
			foreach($configurations as $config) {
				$this->configuration($config);							
			}
		}
		else {
		
			if(!is_string($configurations))
				throw new WrongDataTypeEx("configurations",gettype($configurations),"string");

			if(is_null($configName))
				$configName = $configurations;
				
			$configName = strtolower($configName);
			GameManager::getInstance()->library('configuration')->addConfiguration($configName,$this->loadConfig($configurations));		
		}
	}
	
	/**
	* Alias of the function loadView to be coherent with the way we load content
	*
	* @param $viewName
	* @param $parameters
	* @return string
	*/
	function view($viewName,$parameters=array()) {
		return $this->loadView($viewName,$parameters);		
	}
	
	/**
	* Load a helper class or an helpers' array.
	* Throw WrongDataTypeEx if the parameter $actions is not a string or an array.
	*
	* @param $helpers
	*/	
	public function helper($helpers) {
		
		if(is_array($helpers)) {
			foreach($helpers as $helper) {
				$this->helper($helper);							
			}			
		}
		else {
		
			if(!is_string($helpers))
				throw new WrongDataTypeEx("helpers",gettype($helpers),"string");
	
			$this->loadHelper($helpers);
		}	
	}

	/**
	* Load a library class
	* Throw ClassNotFoundEx
	*
	* @static
	*
	* @param $fileName
	* @return Library
	*/
	private static function loadLibrary($className) {
		
		$folder = GameManager::getPath()."lib/framework/";
		
		$path = $folder.$className.'.php';

		Loader::checkFileValidity($className,$folder);
		
		require_once($path);
		
		if(!class_exists($className))
			throw new ClassNotFoundEx($className,$path);			
					
		return new $className;
	}

	/**
	* Load a action class
	* Throw ClassNotFoundEx
	*
	* @static
	*
	* @param $fileName
	* @return Action
	*/
	private static function loadAction($className) {
		
		$folder = GameManager::getPath()."game/actions/";
		
		$path = $folder.$className.'.php';

		Loader::checkFileValidity($className,$folder);
		
		require_once($path);
		
		if(!class_exists($className))
			throw new ClassNotFoundEx($className,$path);			
					
		return new $className;
	}
	
	/**
	* Load a configuration file
	* Throw ConfigVarNotFoundEx, WrongDataTypeEx
	*
	* @static
	*
	* @param $fileName
	* @return array
	*/
	private static function loadConfig($fileName) {
		
		$folder = GameManager::getPath()."game/config/";
		
		$path = $folder.$fileName.'.php';

		Loader::checkFileValidity($fileName,$folder);
		
		require($path);
		
		if(!isset(${$fileName}))
			throw new ConfigVarNotFoundEx($fileName);
			
		if(!is_array(${$fileName}))
			throw new WrongDataTypeEx($fileName,gettype(${$fileName}),"Array");
					
		return ${$fileName};
	}
	
	/**
	* Load a view with its parameters and returns its content
	* Throw WrongDataTypeEx
	*
	* @static
	*
	* @param $filename
	* @param $para
	* @return string
	*/
	private static function loadView($filename,$param=array()) {
		
		if(!is_string($filename))
			throw new WrongDataTypeEx("filename",gettype($filename),"string");
		
		if(!is_array($param))
			throw new WrongDataTypeEx("param","array",gettype($param),"array");
						
		$folder = GameManager::getPath()."game/views/";
		
		$path = $folder.$filename.'.php';
		
		Loader::checkFileValidity($filename,$folder);

		extract($param,EXTR_OVERWRITE);	// we transform the array of parameters into variables
		
		// we buffer the view and get its content then we clean the buffer
		ob_start();
		require($path);
		$content = ob_get_contents();		
		ob_end_clean();
		
		return $content;
	}
	
	/**
	* Load a helper.
	* Throw WrongDataTypeEx
	*
	* @static
	*
	* @param $helperName
	*/
	private static function loadHelper($helperName) {
		
		if(!is_string($helperName))
			throw new WrongDataTypeEx("helperName",gettype($helperName),"string");
						
		$folder = GameManager::getPath()."lib/helpers/";
		
		$path = $folder.$helperName.'.php';
		
		Loader::checkFileValidity($helperName,$folder);

		require_once($path);
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
}