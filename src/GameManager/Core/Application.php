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

namespace GameManager\Core;

use \GameManager\Core\Component\Request;
use \GameManager\Core\Component\Response;
use \GameManager\Core\Component\Loader;
use \GameManager\Core\Component\Collection;


class Application {
	
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
	 * The loader instance
	 * @var Loader
	 * @access protected
	 */
	protected $loader = null;
	
	/**
	 * The configuration array
	 * @var array
	 * @access protected
	 */
	protected $configuration = null;

	/**
	 * The path of the config files (.php and .xml). 'P' is for Path.
	 * @staticvar
	 */
	const P_CONFIG	= "game/configuration";	
	
	/**
	 * The name of the config file of the application (.php and .xml). 'FN' is for Name.
	 * @staticvar
	 */
	const N_CONFIG_APP	= "application";
	
	/**
	 * Builds the application object
	 * @param sfEventDispatcher $dispatcher the event dispatcher of the application
	 * @param Request $request the request object
	 * @param Response $response the response object
	 */	
	function __construct(\sfEventDispatcher $dispatcher, Request $request, Response $response, Loader $loader) {
		
		$this->dispatcher = $dispatcher;
		$this->request = $request;
		$this->response = $response;
		$this->loader = $loader;
		$this->loader->setApplication($this);
		
		// we create a container that will contains other containers
		$this->container = new Collection("GameManager\Core\Component\Collection");
		$this->container->offsetSet(Loader::T_LIBRARY,new Collection("GameManager\Core\Library\Library"));
		$this->container->offsetSet(Loader::T_CONFIG,new Collection("array"));
		$this->container->offsetSet(Loader::T_ACTION,new Collection("Action"));
		// now we can easily add new containers for models, managers, ...
	}
	
	/**
	 * Initializes the application object by creating the Loader object and autoloading core libraries /configs
	 */
	function init() {		
		$this->load(Loader::T_LIBRARY,array('Router','Hud','Session','Security'));
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
		$this->getLoader()->load($type, $name);
	}

	/**
	 * Returns the container collection instance. If the parameter $containerName is setted, it returns the $containerName collection.
	 * @param string $containerName. Is null by default.
	 * @uses Loader::T_LIBRARY
	 * @uses Loader::T_CONFIG
	 * @uses Loader::T_ACTION
	 */
	public function getContainer($containerName=null) {
		
		if(!is_null($containerName) && $this->container->offsetExists($containerName))
			return $this->container->offsetGet($containerName);
		else
			return $this->container;
	}
	
	/**
	 * Gets the request object
	 * @return Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Gets the response object
	 * @return Response
	 */
	public function getResponse() {
		return $this->response;
	}
	
	/**
	 * Gets the event dispatcher object
	 * @return sfEventDispatcher
	 */
	public function getEventDispatcher() {
		return $this->dispatcher;
	}
	
	/**
	 * Gets the loader object
	 * @return Loader
	 */
	public function getLoader() {
		return $this->loader;
	}
	
	/**
	 * Set the configuration array of the application. If the N_CONFIG_APP.php file does not exist, we dump the N_CONFIG_APP.xml file
	 * @param string $path the path of the N_CONFIG_APP.EXT file
	 */
	public function loadApplicationConfiguration($path) {
		
		$moduleName = $this->getRequest()->getInformation(Request::REQUEST_MODULE);

		$target = $path.'/'.self::N_CONFIG_APP;
		
		if(file_exists($target.'.php')) {
			include($target.'.php');

			if(is_null($moduleName)) { // it has not been setted manually or by the URL
				foreach($application as $module) {					
					if(array_key_exists('default',$module) && $module['default'] == true) {
						break;
					}
				}
				
				if(!array_key_exists('default', $module))
					throw new \RuntimeException('No default module set. Please set manually, via URL or in the config file a default module.');
			}
			else {
				if(!array_key_exists($moduleName, $application))
					throw new \RuntimeException('The module set is not specified in the configuration.');
				
				$module = $application[$moduleName];
			}
			
			$this->getContainer(Loader::T_CONFIG)->offsetSet(self::N_CONFIG_APP,$application[$module['id']]);
		}
		else {
			// create the dumper
			// dump the $target.'.xml' file to the $target.'.php' file
			// call this function recursively in order to set the configuration array
		}
	}
	
	/**
	 * Call all the uninit method of the library
	 */
	public function uninit() {
		foreach($this->getContainer(Loader::T_LIBRARY) as $lib) {
			$lib->uninit();
		}
	}
	
	/**
	 * Returns the absolute path of the application
	 * @return string
	 * @static
	 */
	public static function getPath() {
		return realpath(dirname(__FILE__) . '/../..').'/';
	}
}