<?php

/**
 * Represent a route.
 *
 * @package     GameManager
 * @subpackage  Router
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class Route {
	
	/**
	* The route's id represents the hud's element's ID
	*/
	private $_id;

	/**
	* The route's action represents the action that must be called
	*/	
	private $_action;

	/**
	* The route's method represents the action's method that must be called
	*/	
	private $_method;

	/**
	* The route's parameters represents the method's parameters
	*/	
	private $_params;
	
	/**
	* Construct the object
	*
	* @param $id
	* @param $action
	* @param $method
	* @param $params
	*/
	public function __construct($id,$action,$method,$params="") {
		$this->setId($id);
		$this->setAction($action);
		$this->setMethod($method);
		$this->setParams(Route::paramStringToArray($params));		
	}
	
	/**
	* Transforms a params' string to an params' array.
	*
	* @static
	*
	* @param $paramString
	* @return array
	*/
	private static function paramStringToArray($paramString) {

		if(!is_string($paramString) && !is_null($paramString))
			throw new WrongDataTypeEx("paramString",gettype($paramString),"string");
		
		$params = explode(":",$paramString);

		foreach($params as $key => $value) {
			if(empty($value))
				unset($params[$key]);
		}
		
		return $params;
	}
	
	/**
	* Say if the route is valid or not.
	*/
	public function isValid() {
		return !is_null($this->getId) && !is_null($this->getAction()) && !is_null($this->getMethod());
	}
	
	/**
	* Returns the ID attribute.
	*
	* @return string
	*/
	public function getId() { return $this->_id; }

	/**
	* Returns the action attribute.
	*
	* @return string
	*/	
	public function getAction() { return $this->_action; }

	/**
	* Returns the method attribute.
	*
	* @return string
	*/
	public function getMethod() { return $this->_method; }

	/**
	* Returns the parameter attribute.
	*
	* @return array
	*/	
	public function getParams() { return $this->_params; }

	/**
	* Set the ID attribute.
	*
	* @param string
	*/
	public function setId($str) { 
		$this->_id = $str; 
	}

	/**
	* Set the ID attribute.
	* Throw a WrongDataType.
	*
	* @param string
	*/
	public function setAction($str) { 
		if(!is_string($str))
			throw new WrongDataTypeEx("str",gettype($str),"string");
		$this->_action = $str; 
	}

	/**
	* Set the method attribute.
	* Throw a WrongDataType.
	*
	* @param string
	*/	
	public function setMethod($str) {
		if(!is_string($str))
			throw new WrongDataTypeEx("str",gettype($str),"string");
		$this->_method = $str; 
	}

	/**
	* Set the parameters attribute.
	* Throw a WrongDataType.
	*
	* @param array
	*/	
	public function setParams($tab) { 
		if(!is_array($tab))
			throw new WrongDataTypeEx("tab",gettype($tab),"array");	
		
		$this->_params = $tab; 
	}
}