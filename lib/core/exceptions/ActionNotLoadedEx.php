<?php

/**
 * Exception thrown when the the application tries to access an action that is not loaded (and instanciated).
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class ActionNotLoadedEx extends GameManagerEx {
	
	/**
	 * Construct the object
	 * @param string $fileName the name of the action file that could have not been found
	 */
	function __construct($fileName) {
		parent::__construct("The action ".$fileName." has not been loaded yet.");
	}	
}