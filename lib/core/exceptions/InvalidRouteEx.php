<?php

/**
 * Exception thrown when the Router class meets an invalid route.
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class InvalidRouteEx extends GameManagerEx {
	
	function __construct($cle,$value) {	
		parent::__construct("Invalid route : ".$cle." => ".$value.".");
	}
}