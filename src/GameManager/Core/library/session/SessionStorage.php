<?php

/**
 * Session class.
 * Abstracts the behaviour of the session management. It must be subclassed to implement serialize and unserialize.
 *
 * @package     GameManager
 * @subpackage  Session
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @abstract
 */

namespace GameManager\Core\Library\Session;

abstract class SessionStorage extends \GameManager\Core\Library\Library {
	
	/**
	 * Array of data
	 * @var array
	 */
	protected $session;
	
	/**
	 * Serialize the $session array of data into the session manager
	 * @abstract
	 * @access protected
	 */
	abstract protected function serialize();
		
	/**
	 * Unserialize the session manager into the $session array of data
	 * @abstract
	 * @access protected
	 */
	abstract protected function unserialize();
	
	/**
	 * Initialize the session array by unserializing.
	 * @see SessionStorage::unserialize()
	 */
	protected function init() {
		$this->session = array();
		$this->unserialize();
	}
	
	/**
	 * @inheritDoc
	 * Serialize the data.
	 */
	public function uninit() {
		$this->serialize();
	}
		
	/**
	 * Manage the flash data. This must be called only once.
	 */
	public function manageFlashdata() {
		$this->delete_old_flashdata();		
		$this->mark_flashdata_as_old();
	}
	
	/**
	 * Create a session data
	 * 
	 * @param string $id
	 * @param mixed $data
	 */
	function setData($id,$data) {
		$this->session[$id] = $data;
	}
	
	/**
	 * Get back a session data.
	 * @param string $id
	 * @return mixed false if the ID doesn't exist, the value neither
	 */
	function getData($id) {
		if(!array_key_exists($id,$this->session))
			return false;
			
		return $this->session[$id];
	}
	
	/**
	 * Create a flash data.
	 * 
	 * @param string $id
	 * @param mixed $data
	 */
	function setFlashdata($id,$data) {
		$id = 'flash:new:'.$id;
		$this->setData($id,$data);
	}
	
	/**
	 * Get back a flash data.
	 * @param string $id
	 */
	function getFlashdata($id) {
		$id = 'flash:old:'.$id;
		return $this->getData($id);	
	}
	
	/**
	 * Mark the flash data as old if they has expired
	 */
	private function mark_flashdata_as_old() {
		$data = $this->session;

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
		$this->setData($new_flashdata_key, $value);
	}
	
	/**
	 * Delete the flash data marked as old
	 */
	private function delete_old_flashdata() {		
		$data=$this->session;
				
		foreach ($data as $key => $value)
		{
			if (strpos($key, ':old:'))
			{
				$this->unsetData($key);
			}
		}
	}
	
	/**
	 * Delete a session data. Throw a NonExistentDataEx if the ID doesn't exist.
	 * 
	 * @param $id
	 */
	function unsetData($id)	{
		
		if(!array_key_exists($id,$this->session))
			throw new \OutOfBoundsException("Index ".$id." not in array session.");
			
		unset($this->session[$id]);
	}
}