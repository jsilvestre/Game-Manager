<?php

/**
 * Authentification class. It strongly depends of the class GameManager.
 *
 * @package     GameManager
 * @subpackage  Authentificator
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class Authentificator extends Library {

	/**
	* Configuration's constants
	*/
	const AUTH_CLASS			= "auth_class";
	const AUTH_ID_FIELD			= "auth_id_field";
	const AUTH_LOGIN_FIELD		= "auth_login";
	const AUTH_PASSWORD_FIELD	= "auth_password";
	const AUTH_ENCRYPT			= "auth_encrypt";
	const AUTH_SALT				= "auth_salt";
	
	/**
	* Encryption's type constants
	*/
	const ENCRYPT_MD5		= "encrypt_md5";
	const ENCRYPT_SHA1		= "encrypt_sha1";
	const ENCRYPT_SHA256	= "encrypt_sha256";
	
	private $_config = array(
							self::AUTH_CLASS			=> '',
							self::AUTH_ID_FIELD			=> '',
							self::AUTH_LOGIN_FIELD		=> '',
							self::AUTH_PASSWORD_FIELD	=> '',
							self::AUTH_ENCRYPT			=> '',
							self::AUTH_SALT				=> ''		
						);

	function auth($login,$password) {
		
		foreach($this->_config as $config) {
			if(empty($config))
				throw new GameManagerEx("You must set up the Authentificator in the bootstrap. Please refer to the framework's documentation for more details.");
		}
		
		$table = Doctrine_Core::getTable($this->getConf(self::AUTH_CLASS));
		
		$query = Doctrine_Query::create()->from($this->getConf(self::AUTH_CLASS).' r')
										  ->where('r.'.$this->getConf(self::AUTH_LOGIN_FIELD).' = :login')
										  ->andWhere('r.'.$this->getConf(self::AUTH_PASSWORD_FIELD).' = :pword');
										
		$player = $query->execute(array(
									':login' => $login,
									':pword' => $password
								  ));
		
		if(is_bool($player) && !$player)
			return false;
		
		$id_field = $this->getConf(self::AUTH_ID_FIELD);
	
		// PROBLEM TO SOLVE : $player->field returns a Joueur object and that sucks		

		// without the condition, a WTF error is thrown
		//if(!GameManager::getInstance()->library('session')->getData('id_user'))
			GameManager::getInstance()->library('session')->setData('user','myUserIsCreated');
			
		return true;
	}
	
	function isUserLogged() {
		return !is_bool(GameManager::getInstance()->library('session')->getData('user'));
	}
	
	function setConf($id,$value) {
		if(!is_string($id))
			throw new WrongDataTypeEx("id",gettype($id),"string");
			
		if($id==self::AUTH_ENCRYPT && ($value!=self::ENCRYPT_MD5 && $value!=self::ENCRYPT_SHA1))
			throw new GameManagerEx("You must choose a recognized mode of encryption. Please refer to the framework's documentation for more detail.");
		
		$this->_config[$id] = $value;
	}
	
	function getConf($id) {
		if(!array_key_exists($id,$this->_config))
			throw new NonExistentDataEx(NonExistentDataEx::CLASSIC,$id);
			
		return $this->_config[$id];
	}

	/*function doubleSalt($toHash,$username){
		$password = str_split($toHash,(strlen($toHash)/2)+1);
		var_dump($password);
		$hash = hash('md5', $username.$password[0].'centerSalt'.$password[1]);
		return $hash;
	}*/	
}