<?php

/**
 * Security class.
 *
 * @package     GameManager
 * @subpackage	Security
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @todo refondre et documenter la classe Security
 */

class Security extends Library {
	
	public function protectGlobal() {
		foreach($_POST as $k => $v) {
			$_POST[$this->protectXss($k)] = self::protectXss($v);
		}
		
		foreach($_GET as $k => $v) {
			$_GET[$this->protectXss($k)] = self::protectXss($v);
		}
	}
	
	/**
	* Modify the $data in order to protect it against xss attack.
	*
	* @static
	*
	* @param $data
	* @return string
	*/
	public static function protectXss($data) {
		
		// We should use the Input Class from CodeIgniter in this class.
		
		$data = rawurldecode($data);
		
		return htmlspecialchars($data,ENT_COMPAT,"UTF-8");
	}
	
	/*
		#Quick note about the multi-hud element loading#
		
		if someone replace the link content by the same link everywhere (ie : attacking an opponent: 
		he could strike x times at the same moment). In order to avoid such behaviours, we should secure the request.
		The same 3-uplet action-method-{params} can be called only once a request.
	*/
}