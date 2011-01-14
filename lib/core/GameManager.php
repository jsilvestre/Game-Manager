<?php
/**
 * The main class of the application
 * 
 * Deals with all the aspect of the framework in order to make it works :-)
 *
 * @package     GameManager
 * @subpackage  Application
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class GameManager extends Application {
	
	/**
	 * Event dispatcher instance
	 * @var sfEventDispatcher
	 * @access protected
	 */
	protected $dispatcher = null;
	
	/**
	 * The instance container of the application
	 * It contains all the references of the various class and config array that have been initialized in the application
	 * @var Collection A collection of collection
	 * @access protected
	 */
	protected $container = null;
	
	/**
	 * The request instance
	 * @var Request
	 * @access protected
	 */
	protected $request = null;

	/**
	 * The response instance
	 * @var Response
	 * @access protected
	 */
	protected $response = null;
	
	/**
	 * Builds the application object
	 * @param sfEventDispatcher $dispatcher the event dispatcher of the application
	 * @param Request $request the request object
	 * @param Response $response the response object
	 */	
	function __construct(sfEventDispatcher $dispatcher, Request $request, Response $response) {
		
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->response = $response;
		
		// we create a container that will contains other containers
		$this->container = new Collection("Collection");
		$this->container->offsetSet(Loader::T_LIBRARY,new Collection("Library"));
		$this->container->offsetSet(Loader::T_CONFIG,new Collection("array"));
		$this->container->offsetSet(Loader::T_ACTION,new Collection("Action"));
		// now we can easily add new containers for models, managers, ...
	}
	
	/**
	 * Initializes the application object by creating the Loader object and autoloading core libraries /configs
	 */
	function init() {		
		$this->getContainer(Loader::T_LIBRARY)->offsetSet('loader', new Loader($this)); //now we can use the loading method
		
		$this->load(Loader::T_LIBRARY,array('Router','Session','Security'));
	}
	
	/**
	 * Alias the loading function of the Loader library object.
	 * @param $type
	 * @param $name
	 * @uses Loader::T_LIBRARY
	 * @uses Loader::T_CONFIG
	 * @uses Loader::T_ACTION
	 * @see Loader::load()
	 */
	function load($type,$name) {
		if($this->getContainer(Loader::T_LIBRARY)->offsetExists('loader'))
			$this->getContainer(Loader::T_LIBRARY)->offsetGet('loader')->load($type,$name,$this);
	}

	/**
	 * Returns the container collection instance. If the parameter $containerName is setted, it returns the $containerName collection.
	 * @param string $containerName.Is null by default.
	 * @uses Loader::T_LIBRARY
	 * @uses Loader::T_CONFIG
	 * @uses Loader::T_ACTION
	 */
	function getContainer($containerName=null) {
		
		if(isset($containerName) && $this->container->offsetExists($containerName))
			return $this->container->offsetGet($containerName);
		else
			return $this->container;
	}
	
	/**
	 * Gets the request object
	 * @return Request
	 */
	function getRequest() {
		return $this->request;
	}

	/**
	 * Gets the response object
	 * @return Response
	 */
	function getResponse() {
		return $this->response;
	}
	
	/**
	 * Get the event dispatcher object
	 * @return sfEventDispatcher
	 */
	function getDispatcher() {
		return $this->dispatcher;
	}
}