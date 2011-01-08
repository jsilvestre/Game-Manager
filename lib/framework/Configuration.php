<?php

/**
 * Configuration class. Manage the configuration's arrays and the different boostrapped values
 *
 * @package     GameManager
 * @subpackage  Configuration
 * @author      Joseph Silvestre <contact@jsilvestre.fr
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class Configuration extends Library {
	
	/**
	* Array containing the different configuration arrays
	*/
	private $_configurations = array();
	
	/**
	* Array containing the different javascript files to load
	*/
	private $_javascript = array();

	/**
	* Array containing the different css files to load
	*/	
	private $_css = array();
	
	/**
	* Add a configuration file to the configuration array of the Configuration class.
	* Throw WrongDataTypeEx
	*
	* @param $configName
	* @param $configArray
	*/	
	public function addConfiguration($configName,$configArray) {
		if(!is_string($configName))
			throw new WrongDataTypeEx("configName",gettype($configName),"string");
		
		if(!is_array($configArray))
			throw new WrongDataTypeEx("configArray",gettype($configArray),"array");
			
		$this->_configurations[$configName] = $configArray;		
	}
	
	/**
	* Alias for the configuration's method of the Loader class.
	*
	* @param $configurations
	* @param $configName
	*/
	public function load($configurations,$configName=NULL) {
		GameManager::getInstance()->load->configuration($configurations,$configName);
	}
	
	/**
	 * Get back the array of the requested config file. 
	 * Throw a ConfigNotLoadedEx if the configuration class has not been loaded yet.
	 * 
	 * @param $configName
	 * @return array
	 */	
	function get($configName) {
		if(!array_key_exists($configName,$this->_configurations))
			throw new ConfigNotLoadedEx($configName);
			
		return $this->_configurations[$configName];		
	}
	
	/**
	* Add a css file to the css filepath' array
	* Throw WrongDataTypeEx, FileNotFoundEx
	*
	* @param $css
	*/
	function addCss($css) {
		
		if(is_array($css)){
			foreach($css as $cssName) {
				$this->addCss($cssName);
			}
		}
		else {
			if(!is_string($css))
				throw new WrongDataTypeEx("css",gettype($css),"string");

			$baseConf = $this->get('base');
						
			$path = sfFinder::type('file')->name($css.'.css')->in($baseConf["css_folder"]);
		
			if(count($path)==0 || !file_exists($path[0]))
				throw new FileNotFoundEx($baseConf["css_folder"].'/*/'.$css.'.css');
			
			$this->_css[] = substr($path[0],strlen(GameManager::getPath()),strlen($path[0]));
		}		
	}
	
	/**
	* Add a javascript file to the javascript filepath' array
	* Throw WrongDataTypeEx, FileNotFoundEx
	*
	* @param $javascript
	*/
	function addJavascript($javascript) {
		
		if(is_array($javascript)){
			foreach($javascript as $jsName) {
				$this->addJavascript($jsName);
			}
		}
		else {
			if(!is_string($javascript))
				throw new WrongDataTypeEx("javascript",gettype($javascript),"string");
			
			$baseConf = $this->get('base');
			
			$path = sfFinder::type('file')->name($javascript.'.js')->in($baseConf["javascript_folder"]);
		
			if(count($path)==0 || !file_exists($path[0]))
				throw new FileNotFoundEx($baseConf["javascript_folder"].'/*/'.$javascript.'.js');
			
			$this->_javascript[] = substr($path[0],strlen(GameManager::getPath()),strlen($path[0]));
		}		
	}
	
	function getCss() {

		$css = "";

		foreach($this->_css as $file) {
			$css.='<link rel="stylesheet" type="text/css" media="all" href="'.$file.'" />';
		}
		
		return $css;
	}

	function getJavascript() {

		$js = "";

		foreach($this->_javascript as $file) {
			$js.='<script type="text/javascript" src="'.$file.'"></script>';
		}
		
		return $js;
	}	
}