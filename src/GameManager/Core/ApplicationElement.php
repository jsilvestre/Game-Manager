<?php

/**
 * Abstract class for application elements
 *
 * @package     GameManager
 * @subpackage  Application
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @abstract
 */

namespace GameManager\Core;

abstract class ApplicationElement {

	/**
	 * The application instance
	 * @var Application
	 * @access private
	 */
	private $_application;
	
	/**
	 * Constructor
	 */
	function __construct(Application $app) {
		
		$this->_application = $app;
		
		$this->init();
	}
	
	/**
	 * Initializes the library object. Redefine it if needed
	 * @access protected
	 */
	protected function init() {}
	
	/**
	 * This is the opposit of the init method : it will be called at the end of the program.
	 */
	public function uninit() {}
	
	/**
	 * Get the event dispatcher instance
	 * @return sfEventDispatcher
	 * @access protected
	 * @uses Application::getEventDispatcher();
	 */
	protected function getEventDispatcher() {
		return $this->_application->getEventDispatcher();
	}
	
	/**
	 * Load an element into the application. Alias the application load method.
	 * @param string $type the element type
	 * @param string $name
	 * @access protected
	 * @uses Application::load()
	 */
	protected function load($type,$name) {
		return $this->_application->load($type, $name);
	}
	
	/**
	 * Alias the application getContainer method.
	 * @param string containerName
	 * @uses Application::getContainer()
	 * @access protected
	 */
	protected function getContainer($containerName) {
		return $this->_application->getContainer($containerName);
	}
	
	/**
	 * Gets the request object
	 * @return Request
	 */
	protected function getRequest() {
		return $this->_application->getRequest();
	}
	
	/**
	 * Gets the response object
	 * @return Response
	 */
	protected function getResponse() {
		return $this->_application->getResponse();
	}
}