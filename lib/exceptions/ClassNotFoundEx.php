<?php

/**
 * Exception thrown when a class has not been found during autoloading or loading.
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
	 * @param string $className the name of the concerned class
	 * @param string $path the path where the class couldn't have been found
	 */
	function __construct($className, $path) {
		parent::__construct("Unable to find the class ".$className." in ".$path);		
	}	
}