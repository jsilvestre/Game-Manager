<?php

/**
 * Exception thrown when the application meet an unexpected type.
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class WrongDataTypeEx extends GameManagerEx {
	
	function __construct($variableName, $variableType, $expectedType) {
		parent::__construct("Wrong data type for the variable ".$variableName.". Expected ".$expectedType.", given ".$variableType.".");		
	}	
}