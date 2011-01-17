<?php

namespace GameManager\Test\Core\Component;

use GameManager\Core\Application;

use \GameManager\Core\Component as Comp;
use \GameManager\Core\Library as Lib;

class TestCollection extends \PHPUnit_Framework_TestCase {
	
	protected $collection;
	protected $obj;
	
	public function setUp() {
		$this->collection = new Comp\Collection('stdClass');
		$this->obj = new \stdClass();
	}
	
	public function testConstruct() {
		$this->assertEquals($this->collection->getType(),'stdClass');
		$this->assertEquals($this->collection->count(),0);
	}
	
	public function testIsTypeValid() {
		
		// with the setUp method, the collection is a stdClass collection.
		$this->assertTrue($this->collection->isTypeValid(new \stdClass()));
		$this->assertFalse($this->collection->isTypeValid(new TestCollection()));
		
		$this->collection = new Comp\Collection('GameManager\Core\Library\Library');
		$app = new Application(new \sfEventDispatcher(), new Comp\Request(), new Comp\Response(), new Comp\Loader());
		$this->assertTrue($this->collection->isTypeValid(new Lib\Router($app)));
		
		$this->collection = new Comp\Collection('string');
		$this->assertTrue($this->collection->isTypeValid('coucou'));
		
		$this->collection = new Comp\Collection('int');
		$this->assertTrue($this->collection->isTypeValid(3));
		$this->assertFalse($this->collection->isTypeValid(3.0));
		
		$this->collection = new Comp\Collection('array');
		$this->assertTrue($this->collection->isTypeValid(array(1,2)));
	}
	
	/**
	 * @expectedException RuntimeException
	 */
	public function testIsTypeValidErrorCase() {
		// the float type is not treated by the object
		$this->collection = new Comp\Collection('float');
		$this->setExpectedException('RuntimeException');
		$this->collection->isTypeValid(3.0);		
	}
	
	public function testOffsetSet() {	// also covers the append method	
		$this->collection->offsetSet('randomindex',new \stdClass());	
		$this->assertTrue($this->collection->offsetExists('randomindex'));	
	}	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testOffsetSetErrorCase() { // also covers the append method
		$this->collection->offsetSet('randomindex',new TestCollection());
	}
	
}