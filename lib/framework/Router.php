<?php

/**
 * Routing class.
 * The format of an URL is : load=part&id=action-methode-params&id=....
 * The parameters' pattern is : param1:param2:param3
 *
 * @package     GameManager
 * @subpackage  Router
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class Router extends Library {
	
	/**
	* Type of request's constants
	*/
	const T_COMPLETE_LOADING 	= "completeLoading";	
	const T_PART_LOADING		= "partLoading";
		
	/**
	* Type of the request
	*/
	private static $_requestType;
	
	/**
	* Array containing the routes
	*/
	private $_routes;
	
	/**
	* Initialize the library.
	*/
	function init() {
		$this->setRoutes(array());
		$this->setRequestType();
	}

	/**
	* Create the routes of the application according to the requested URL.
	* Throw an InvalidRouteEx if a url is not valid.
	*/
	public function processRouting() {
				
		$routes = array();
		
		foreach($_GET as $id => $target) {
			
			// do not secure the parameters themselves, only check the action-method-params pattern
			$pattern = "#^[a-z/]+((-[a-z]+)?|(-[a-z]+){1}(-[a-z0-9:]+)?)$#";
			
			if(preg_match($pattern,$target)) {
				
				$info = explode("-",$target);
				
				if(isset($info[0]) && !empty($info[0])) // first element = action
					$action = $info[0];
				else
					$action = NULL;
					
				if(isset($info[1]) && !empty($info[1])) // second element = method
					$method = $info[1];
				else
					$method = "index";
					
				if(isset($info[2]) && !empty($info[2])) // third element = parameters				
					$params = $info[2];
				else
					$params = NULL;

				$routes[$id] = new Route($id,$action,$method,$params);
			}
			else {
				throw new InvalidRouteEx($id,$target);
			}
		}
		
		$this->setRoutes($routes);
	}
	
	/**
	* Create the url according to the array $targets.
	* Throw WrongDataTypeEx.
	*
	* @static
	*
	* @param $targets
	* @return string
	*/
	static function makeURL($targets) {
		
		if(!is_array($targets))
			throw new WrongDataTypeEx("targets",gettype($targets),"array");
		
		$url = "requestType=".self::T_PART_LOADING.'&';
		
		foreach($targets as $target) {
			
			if(isset($target[0])) { // adding the id
				$url.=$target[0].'=';
			
				if(isset($target[1])) { // adding the action
					$url.=$target[1].'-';				
			
					if(isset($target[2])) { // adding the method
						$url.=$target[2].'-';
				
						if(isset($target[3])) { // adding the parameters

							if(!is_array($target[3]))
								throw new WrongDataTypeEx('target[3]',gettype($target[3]),"array");

							$params = "";

							foreach($target[3] as $param) {
								$params.=$param.':';
							}
							
							$url.=substr($params,0,strlen($params)-1);
						}
						else
							$url = substr($url,0,strlen($url)-1);
					}
					else
						$url = substr($url,0,strlen($url)-1);
				}
				$url.='&';				
			}
		}
		$url = substr($url,0,strlen($url)-1);
		
		return $url;
	}

	/**
	* Set the request's type.
	*/	
	private function setRequestType() {
		
		if(isset($_GET["requestType"]) && $_GET["requestType"]==self::T_PART_LOADING) {
			static::$_requestType = self::T_PART_LOADING;
			unset($_GET["requestType"]);
		}
		else
			static::$_requestType = self::T_COMPLETE_LOADING;	
	}
	
	/**
	* Returns the routes' array
	*
	* @return array
	*/
	function getRoutes() {
		return $this->_routes;
	}
	
	/**
	* Set the routes' array.
	* Throw a WrongDataTypeEx if the parameter is not an array of Route.
	*
	* @param $routes
	*/
	function setRoutes($routes) {
		if(!is_array($routes))
			throw new WrongDataTypeEx('routes',gettype($routes),"array");
			
		foreach($routes as $route) {
			if(!($route instanceof Route))
				throw new WrongDataTypeEx($route,gettype($route),"Route");
		}
			
		$this->_routes = $routes;
	}
	
	/**
	* Detect if the requested url corresponds to a full load (complete loading of the page or part loading)
	*
	* @static
	*
	* @return boolean
	*/
	static function isCompleteLoading() {
		return static::$_requestType==self::T_COMPLETE_LOADING;
	}
}