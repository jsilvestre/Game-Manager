<?php

/**
 * Session class. Manage the session's data for the application.
 *
 * @package     GameManager
 * @subpackage  Session
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @todo revoir la documentation de la classe Session
 */

namespace GameManager\Core\Library;

class Session extends Library {
		
	/**
	 * Manage the flash data. This must be called only once.
	 */
	function manageFlashdata() {
		$this->delete_old_flashdata();		
		$this->mark_flashdata_as_old();
	}
	
	/**
	 * Create a session data
	 * 
	 * @param $id
	 * @param $data
	 */
	function setData($id,$data) {
		$_SESSION[$id] = $data;
	}
	
	/**
	 * Get back a session data. Returns false if the id doesn't exist.
	 *
	 * @param $id
	 */
	function getData($id) {
		if(!array_key_exists($id,$_SESSION))
			return false;
			
		return $_SESSION[$id];
	}
	
	/**
	 * Create a flash data
	 * 
	 * @param $id
	 * @param $data
	 */
	function setFlashdata($id,$data) {
		$index = 'flash:new:'.$id;
		$this->setData($id,$data);
	}
	
	/**
	 * Get back a flash data. Throw a NonExistentDataEx if the id doesn't exist.
	 * 
	 * @param $id
	 */
	function getFlashdata($id) {
		$index = 'flash:old:'.$id;
		return $this->getData($id);	
	}
	
	/**
	 * Mark the flash data as old if they has expired
	 */
	private function mark_flashdata_as_old() {
		$data = $_SESSION;

		foreach ($data as $name => $value) {
		
			$parts = explode(':new:', $name);
			if (is_array($parts) && count($parts) === 2)
			{
				$new_name = 'flash:old:'.$parts[1];
				$this->setData($new_name, $value);
				$this->unsetData($name);
			}
		}
	}	
	
	/**
	 * Extending the flash data's validity duration
	 * 
	 * @param $id
	 */
	function keepFlashdata($id) {
		$old_flashdata_key = 'flash:old:'.$id;
		$value = $this->getData($old_flashdata_key);

		$new_flashdata_key = 'flash:new:'.$id;
		$this->setSata($new_flashdata_key, $value);
	}
	
	/**
	 * Delete the flash data marked as old
	 */
	private function delete_old_flashdata() {		
		$data=$_SESSION;
				
		foreach ($data as $key => $value)
		{
			if (strpos($key, ':old:'))
			{
				$this->unset_data($key);
			}
		}
	}
	
	/**
	 * Delete a session data. Throw a NonExistentDataEx if the ID doesn't exist.
	 * 
	 * @param $id
	 */
	function unsetData($id)	{
		
		if(!array_key_exists($id,$_SESSION))
			throw NonExistentDataEx(NonExistentData::SESSION,$id);
			
		unset($_SESSION[$id]);
	}
}