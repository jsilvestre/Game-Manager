<?php
/**
 * Abstract the core class. It gives the Singleton behavior.
 * 
 * Abstract all the Singleton behavior of the Application class
 *
 * @package     GameManager
 * @subpackage  Application
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://destrukalips.jsilvestre.fr
 * @since       1.0
 */

abstract class Singleton {
	
	/**
	 * Singleton instance
	 * @access protected
	 * @staticvar Singleton
	 */	
	protected static $_instance;
	
	/**
	 * Constructor.
	 * @access private
	 * @throws Exception
	 * @final
	 */
	final private function __construct() {
		
		if(static::$_instance instanceof static)
			throw new Exception("A singleton can only be instanciated once.");
	}
	
	/**
	 * Cloning method : cloning a singleton is impossible
	 * @access private
	 * @throws Exception
	 * @final
	 */
	final private function __clone() {
		throw new Exception("A singleton can not be cloned");
	}
	
	/**
	 * Return the instance of the class
	 * 
	 * @return Singleton
	 * @final
	 */
	final public static function &getInstance() {
		
		if (!(static::$_instance instanceof static))
            static::$_instance = new static();
 
        return static::$_instance;
	}
}