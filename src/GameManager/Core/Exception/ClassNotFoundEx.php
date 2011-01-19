<?php

/**
 * Exception thrown when a class has not been found.
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class ClassNotFoundEx extends GameManagerEx {
	
	/**
	 * Construct the exception
	 * @param string $className the name of the class
	 * @param string $path the path where the file have not been found
	 */
	function __construct($className, $path) {
		parent::__construct("Unable to find the class ".$className." in ".$path);		
	}	
}