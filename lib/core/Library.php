<?php

/**
 * Abstract class for libraries
 *
 * @package     GameManager
 * @subpackage  Library
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @abstract
 */

abstract class Library {

	/**
	 * The application instance
	 * @var GameManager
	 * @access private
	 */
	private $_application;
	
	/**
	 * Constructor
	 */
	function __construct(GameManager $app) {
		
		$this->_application = $app;
		
		$this->init();
	}
	
	/**
	 * Initializes the library object. Redefine it if needed
	 * @access protected
	 */
	protected function init() {}
	
	/**
	 * Get the event dispatcher instance
	 * @return sfEventDispatcher
	 * @access protected
	 * @uses GameManager::getEventDispatcher();
	 */
	protected function getEventDispatcher() {
		return $this->_application->getEventDispatcher();
	}
	
	/**
	 * Load an element into the application. Alias the application load method.
	 * @param string $type the element type
	 * @param string $name
	 * @access protected
	 * @uses GameManager::load()
	 */
	protected function load($type,$name) {
		return $this->_application->load($type, $name);
	}
	
	/**
	 * Alias the application getContainer method.
	 * @param string containerName
	 * @uses GameManager::getContainer()
	 * @access protected
	 */
	protected function getContainer($containerName) {
		return $this->_application->getContainer($containerName);
	}
}