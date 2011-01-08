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
	private $application;
	
	/**
	 * Constructor
	 */
	function __construct(GameManager $app) {
		
		$this->application = $app;
		
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
	 */
	protected function getEventDispatcher() {
		return $this->application->getDispatcher();
	}
	
	/**
	 * Load an element into the application.
	 * @param string $type the element type
	 * @param string $name
	 * @access protected
	 * @uses Loader::T_LIBRARY
	 * @uses Loader::T_CONFIG
	 * @uses Loader::T_ACTION
	 */
	protected function load($type,$name) {
		return $this->application->load($type, $name);
	}
	
}