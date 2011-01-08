<?php

/**
 * Exception thrown when an ID can't be found in a global array.
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class NonExistentDataEx extends GameManagerEx {
	
	/**
	* The concerned array is $_SESSION
	*/
	const SESSION = "SESSION";

	/**
	* The concerned array is $_GET
	*/	
	const GET = "GET";
	
	/**
	* The concerned array is $_POST
	*/
	const POST = "POST";
	
	/**
	* The concerned array is a random array
	*/
	const CLASSIC = "CLASSIC";
	
	public function __construct($global,$id) {		
		parent::__construct("The id ".$id." can't be found in ".$global);
	}
}