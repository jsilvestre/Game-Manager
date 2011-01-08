<?php

/**
 * Exception thrown when the the application tries to get access to a configuration file that is not loaded.
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class ConfigNotLoadedEx extends GameManagerEx {
	
	function __construct($fileName) {
		parent::__construct("The configuration file ".$fileName." has not been loaded yet.");
	}	
}