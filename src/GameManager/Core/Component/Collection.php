<?php
/**
 * Collection class.
 * 
 * The collection class extends the ArrayObject class to provide a type check.
 *
 * @package     GameManager
 * @subpackage	Collection
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

namespace GameManager\Core\Component;

class Collection extends \ArrayObject {
	
	/**
	 * The collection type
	 * @var class|int|array|string
	 * @access private
	 */
	private $_type;
	
	/**
	 * Constructs the collection
	 * @param mixed $type it can be a class or a primal type
	 */	
	function __construct($type) {
		
		$this->_type = $type;
		
		parent::__construct(array());
	}
	
	/**
	 * Checks the type of a value.
	 * @param mixed $value
	 * @throws RuntimeException if the collection type is not correct
	 * @return bool true if the test pass, false if it fail
	 */
	public function isTypeValid($value) {
		
		$colType = $this->getType();

		if(is_object($value) && class_exists(get_class($value)) && class_exists($colType)) {
					
			$pass = get_class($value) == $colType || is_subclass_of($value, $colType);
		}
		else if($colType=="array")
			$pass = is_array($value);
		else if($colType=="string")
			$pass = is_string($value);
		else if($colType=="int")
			$pass = is_int($value);
		else
			throw new \RuntimeException("The collection type must be a class,array,string or int");
			
		return $pass;		
	}
	
	/**
	 * Get the collection type
	 * @return mixed
	 */
	public function getType() {
		return $this->_type;
	}
	
	/**
	 * Redefines the offsetSet method of the ArrayObject class
	 * @see ArrayObject::offsetSet()
	 * @throws InvalidArgumentException if the type check fail
	 */
	public function offsetSet($index, $newval) {
		
		if($this->isTypeValid($newval)) {				
			parent::offsetSet(strtolower($index), $newval);
		}
		else {
			if(is_object($newval)) {
				$type = get_class($newval);
			}
			else {
				$type = gettype($newval);
			}
			throw new \InvalidArgumentException('The $value type must be '.$this->getType().'. It is '.$type.'.');
		}
	}
	
	/**
	 * Redefines the append method of the ArrayObject class
	 * @see ArrayObject::append()
	 * @throws InvalidArgumentException if the type check fail
	 */
	public function append($value) {
		
		if($this->isTypeValid($newval)) {				
			parent::append($index, $newval);
		}
		else {
			if(is_object($newval)) {
				$type = get_class($newval);
			}
			else {
				$type = gettype($newval);
			}
			throw new \InvalidArgumentException('The $value type must be '.$this->getType().'. It is '.$type.'.');
		}
	}
	
}