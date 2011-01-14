<?php
/**
 * Request class.
 * 
 * Represents what the url has requested to the application.
 * The application provides a Request object to its Router library that will set it properly.
 *
 * @package     GameManager
 * @subpackage	Request
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class Request {
	
	/**
	 * Collection of Routes
	 * @var array of Route object
	 * @access protected
	 */
	private $routes;
	
	/**
	 * Collection of Information about the user and the url itself that request the URL
	 * @var array
	 * @access private
	 */
	protected $information;
	
	/**
	 * Index for the User IP information
	 */
	const USER_IP	= "user_ip";	
	
	/**
	 * Index for the request type "complete loading" or "part loading"
	 * @see Router::T_COMPLETE_LOADING
	 * @see Router::T_PART_LOADING
	 * @see Router::isCompleteLoading()
	 */
	const REQUEST_TYPE	= "type_of_request";
	
	/**
	 * Constructor
	 */
	function __construct() {
		$this->_init();
	}
	
	/**
	 * Initialize the object
	 * @access private
	 */
	private function _init() {
		$this->routes = array();
		$this->information = array(
								self::USER_IP		=> null,
								self::REQUEST_TYPE	=> Router::T_COMPLETE_LOADING	
							);
	}
	
	/**
	 * Add a route object to the routes collection 
	 * @param Route $route
	 * @throws Exception
	 */
	function addRoute(Route $route) {
		if(array_key_exists($route->getId(), $this->routes))
			throw new RuntimeException("can't have two routes with the same ID target");
			
		$this->routes[$route->getId()] = $route;		
	}
	
	/**
	 * Get a route object from the routes collection
	 * @param string $index
	 * @return Route the route object of ID $index
	 * @throws OutOfBoundsException
	 */
	function getRoute($index) {
		if(!array_key_exists($index, $this->routes))
			throw new OutOfBoundsException("Index ".$index." not in array _routes");
			
		return $this->routes[$index];
	}

	/**
	 * Get the routes collection
	 * @return array
	 */
	function getAllRoutes() {
		return $this->routes;
	}
	
	/**
	 * Get the size of the routes collection
	 * @return int
	 */
	function requestSize() {
		return count($this->routes);
	}
	
	/**
	 * Say if the routes collection is empty.
	 * @return bool true if the routes collection is empty, false neither
	 */
	function isRequestEmpty() {
		return $this->requestSize()==0;
	}
	
	/**
	 * Set an information to the information collection.
	 * @param string $index
	 * @param mixed $value
	 * @throws OutOfBoundsException if the index doesn't match a default index
	 * @uses Constants of the Request class. 
	 */
	function setInformation($index,$value) {
		if(!array_key_exists($index, $this->information))
			throw new OutOfBoundsException("Index ".$index." not in array _information");
			
		$this->information[$index] = $value;
	}
	
	/**
	 * Set an array of information.
	 * @param array $information
	 * @uses setInformation
	 */
	function setAllInformation(array $information) {
		
		foreach($information as $index => $value) {
			$this->setInformation($index,$value);
		}
	}
	
	/**
	 * Get an information from the information collection
	 * @param string $index
	 * @return mixed
	 * @throws OutOfBoundsException if the index doesn't match a default index
	 * @uses Constants of the Request class.
	 */
	function getInformation($index) {
		if(!array_key_exists($index, $this->information))
			throw new OutOfBoundsException("Index ".$index." not in array _information");
			
		return $this->information[$index];
	}
	
	/**
	 * Get the information collection
	 * @return array
	 */
	function getAllInformation() {
		return $this->information;
	}
}