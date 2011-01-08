<?php

/**
 * Abstract Action class. All the action files must inherit this class
 *
 * @package     GameManager
 * @subpackage	Action
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

abstract class Action {

	/**
	* Alias for loading a view
	*
	* @param $viewName
	* @param $params
	* @return string
	*/
	protected function view($viewName,$params=array()) {
		return GameManager::getInstance()->load->view($viewName,$params);
	}
	
	
	protected function getUserInfo() {
		
		
	}
}