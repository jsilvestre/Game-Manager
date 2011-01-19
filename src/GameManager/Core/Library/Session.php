<?php

/**
 * Session class. 
 * Manages the session data for the application. We use the global $_SESSION to manage session with this class.
 * You can redefine serialize() and unserialize() to use database or cookies. 
 *
 * @package     GameManager
 * @subpackage  Session
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @abstract
 */

namespace GameManager\Core\Library;

class Session extends Session\SessionStorage {

	/**
	 * @inheritDoc
	 */
	protected function serialize() {

		foreach($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		
		foreach($this->session as $key => $value) {
			$_SESSION[$key] = $value;
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function unserialize() {
		
		foreach($_SESSION as $key => $value) {
			$this->session[$key] = $value;
		}
	}
}