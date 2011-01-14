<?php

/**
 * Represents a route.
 * 
 * The routes are managed by the applicaiton (router and hud) in order to represents the requested URL.
 * They are carried by the request object. 
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
	* The route ID represents the hud element ID
	* @var string
	*/
	private $_id;

	/**
	* The route action represents the action that must be called
	* @var string
	*/	
	private $_action;

	/**
	* The route method represents the action's method that must be called
	* @var string
	*/	
	private $_method;

	/**
	* The route parameters represents the method parameters
	* @var array
	*/	
	private $_params;
	
	/**
	* Builds the object
	*
	* @param string $id
	* @param string $action
	* @param string $method
	* @param string $params
	*/
	public function __construct($id,$action,$method,$params="") {
		$this->setParams(array());
		$this->init($id,$action,$method,$params);		
	}
	
	/**
	* Initializes the object
	*
	* @param string $id
	* @param string $action
	* @param string $method
	* @param string $params
	*/
	protected function init($id,$action,$method,$params) {
		$this->setId($id);
		$this->setAction($action);
		$this->setMethod($method);
		$this->setParams(Route::paramStringToArray($params));		
	}
	
	/**
	* Transforms a params string into a params array.
	* @static
	* @param string $paramString
	* @return array
	* @access private
	*/
	private static function paramStringToArray($paramString) {

		$params = explode(":",$paramString);

		/**
		 * @deprecated now that the pattern for URL parameter has been corrected
		 */
		foreach($params as $key => $value) {
			if(empty($value))
				unset($params[$key]);
		}
		
		return $params;
	}
	
	/**
	* Says if the route is valid or not.
	*/
	public function isValid() {
		return !is_null($this->getId()) && !is_null($this->getAction()) && !is_null($this->getMethod());
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
	* @param string $str
	*/
	public function setId($str) { 
		$this->_id = $str; 
	}

	/**
	* Set the ID attribute.
	* @param string $str
	*/
	public function setAction($str) { 
		$this->_action = $str; 
	}

	/**
	* Set the method attribute.
	* @param string $str
	*/	
	public function setMethod($str) {
		$this->_method = $str; 
	}

	/**
	* Set the parameters attribute.
	* @param array $tab
	*/	
	public function setParams(array $tab) {		
		$this->_params = $tab; 
	}
}