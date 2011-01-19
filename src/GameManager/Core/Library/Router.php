<?php

/**
 * Routing class.
 * 
 * Handle the URL in order to create a Request object for the application
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

namespace GameManager\Core\Library;

use \GameManager\Core\Component\Request;
use \GameManager\Core\Exception\InvalidRouteEx;

class Router extends Library {
	
	/**
	* Type of request constant : complete loading. "T" is for "Type".
	*/
	const T_COMPLETE_LOADING 	= "completeLoading";

	/**
	 * Type of request constant : part loading. "T" is for "Type".
	 */
	const T_PART_LOADING		= "partLoading";
	
	/**
	 * The string for the requestType parameter in the URL. "U" is for "URL".
	 */
	const U_REQUEST_TYPE		= "requestType";
	
	/**
	 * The string for the module parameter in the URL. "U" is for "URL".
	 */
	const U_REQUEST_MODULE		= "module";

	/**
	 * The array containing the options for the URL. It will basically be the GET global.
	 * @var array
	 * @access protected
	 */
	protected $sourceArray;
	
	/**
	 * {@inheritdoc}
	 */
	protected function init() {
		$this->setSourceArray(array());
	}
	
	/**
	 * Retrieve and set the information of the request.
	 */
	public function processInformation() {
		
		// we must call these functions to remove the parameters in the URL
		$type = $this->_getRequestTypeFromUrl();
		$module = $this->_getRequestModuleFromUrl();
		
		$this->getRequest()->setInformation(Request::REQUEST_TYPE, $type);
		
		if(array_key_exists('REMOTE_ADDR', $_SERVER)) // to make the tests work properly : there is no REMOTE_ADDR index during the tests and it throws an error
			$this->getRequest()->setInformation(Request::USER_IP, $_SERVER["REMOTE_ADDR"]);
		
		// if the default module has been set manually, we don't use the URL parameter
		if($this->getRequest()->getInformation(Request::REQUEST_MODULE) == null)
			$this->getRequest()->setInformation(Request::REQUEST_MODULE, $module);
	}
	
	/**
	* Creates the routes.
	* Creates the routes from the URL and add them to the Request object
	* @dispatches router.after_routing sfEvent at the end of the method
	* @throws InvalidRouteEx if the url is not valid.
	*/
	public function processRouting() {
						
		foreach($this->sourceArray as $id => $target) {
			
			// do not secure the parameters themselves, only check the action-method-param1:param2:... pattern
			$pattern = "#^[a-z/]+((-[a-z]+)?|(-[a-z]+){1}(-(:?([a-z0-9])+)+)?)$#";
			
			if(preg_match($pattern,$target)) {
				
				$info = explode("-",$target);
				
				if(isset($info[0]) && !empty($info[0])) // first element = action
					$action = $info[0];
				else
					$action = null;
					
				if(isset($info[1]) && !empty($info[1])) // second element = method
					$method = $info[1];
				else
					$method = "index";
					
				if(isset($info[2]) && !empty($info[2])) // third element = parameters				
					$params = $info[2];
				else
					$params = null;

				$this->getRequest()->addRoute(new Router\Route($id,$action,$method,$params));

			}
			else {
				throw new \GameManager\Core\Exception\InvalidRouteEx($id,$target);
			}
		}

		// we notify that routing is done and we pass the Request object to the listeners.
		$this->getEventDispatcher()->notify(new \sfEvent($this->getRequest(), 'router.after_routing'));
	}
	
	/**
	* Create the url parameters according to the array $targets.
	* 
	* For instance, you can have this kind of array
	* <code>
	* 	<?php
	* 		// will link to one hud element
	* 		$targets = array(array('id','action','method',array('param1','param2')));
	* 		// will link to two hud elements
	* 		$targets2 = array(array('id','action','method'),array('id2','action2','method2'));
	* 	?>
	* </code>
	* 
	* @static
	* @param array $targets of url array
	* @return string the well formed URL
	*/
	static function makeURL(array $targets) {
		
		$url = self::U_REQUEST_TYPE."=".self::T_PART_LOADING.'&';
		
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
	* Retrieve the request type from url and returns it. Then it will destroy the index in the source array.
	* @access private
	* @return string
	*/	
	private function _getRequestTypeFromUrl() {
		
		if(isset($this->sourceArray[self::U_REQUEST_TYPE])) {
			$requestType = $this->sourceArray[self::U_REQUEST_TYPE];
			unset($this->sourceArray[self::U_REQUEST_TYPE]);
		}
		
		if(isset($requestType) && $requestType==self::T_PART_LOADING) {
			return self::T_PART_LOADING;
		}
		else {			
			return self::T_COMPLETE_LOADING;	
		}
	}
	
	/**
	 * Retrieve the request module form url and returns it. Then it will destroy the index in the source array.
	 * @access private
	 * @return string
	 */
	private function _getRequestModuleFromUrl() {
		
		$requestModule = null;
		
		if(isset($this->sourceArray[self::U_REQUEST_MODULE])) {
			$requestModule = $this->sourceArray[self::U_REQUEST_MODULE];
			unset($this->sourceArray[self::U_REQUEST_MODULE]);
		}
		
		return $requestModule;				
	}
	
	/**
	 * Sets the sourceArray attribute
	 * @param array $sourceArray
	 */
	public function setSourceArray(array $sourceArray) {
		$this->sourceArray = $sourceArray;
	}
}