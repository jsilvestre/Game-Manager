<?php

/**
 * Interface for displayable object.
 *
 * @package     GameManager
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

interface IDisplayObject {
	
	/**
	* Generate an output
	*/
	function render();
}

?>