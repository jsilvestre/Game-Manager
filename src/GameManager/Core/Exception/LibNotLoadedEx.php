<?php

/**
 * Exception thrown when the the application tries to access a library that is not loaded (and instanciated).
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class LibNotLoadedEx extends GameManagerEx {
	
	function __construct($fileName) {
		parent::__construct("The library ".$fileName." has not been loaded yet.");
	}	
}