<?php

/**
 * Exception thrown when a file has not been found.
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class FileNotFoundEx extends GameManagerEx {
	
	function __construct($path) {
		parent::__construct("Unable to find the file at ".$path.".");		
	}	
}