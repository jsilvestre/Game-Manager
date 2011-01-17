<?php

/**
 * Exception thrown when a configuration file is loaded and that there is no matching array inside.
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class ConfigVarNotFoundEx extends GameManagerEx {
	
	/**
	 * Construct the exception
	 * @param string $fileName the name of the configuration file
	 */
	function __construct($fileName) {
		parent::__construct("The configuration file ".$fileName." has no configuration array whose name matches ".$fileName.".");
	}	
}