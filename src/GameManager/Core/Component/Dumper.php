<?php

/**
 * Abstract class for dumpers
 *
 * @package     GameManager
 * @subpackage  Component
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @abstract
 */

namespace GameManager\Core\Component;

abstract class Dumper {

	/**
	 * Process the dumping
	 */
	abstract public function dump();
	
	/**
	 * Remove the last character of the $string if its in the $char array.
	 * @param string $string
	 * @param array $char
	 * @return the modified (or not) string.
	 * @static
	 */
	public static function removeLastChar($string, array $char) {
		
		if(in_array($string[strlen($string)-1],$char)) {
			$string = substr($string,0,strlen($string)-1);
		}		
		
		return $string;
	}
}