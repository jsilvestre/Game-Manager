<?php

/**
 * Handle the hooks.
 *
 * @package     GameManager
 * @subpackage  Hook
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class HookHandler extends Library {
	
	private $_hooks = array();
	
	
	
	function addHook($hook) {
		$this->_hooks[] = $hook;
	}
	
	function execute() {
		
		foreach($this->_hooks as $hook) {
			
			
			
			
		}
				
	}
	
}