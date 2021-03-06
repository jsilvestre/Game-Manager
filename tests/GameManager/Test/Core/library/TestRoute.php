<?php

namespace GameManager\Test\Core\Library;

use \GameManager\Core\Component as Comp;
use \GameManager\Core\Library as Lib;

class TestRoute extends \PHPUnit_Framework_TestCase {
	
	protected $route;
	
	public function setUp() {
		$this->route = new Lib\Router\Route('id','action','method','param1:param2');
	}
	
	//cover the paramToString function and getters
	public function testConstruct() {		
		// the comparison values are in the setUp method
		$this->assertEquals($this->route->getId(),'id');
		$this->assertEquals($this->route->getAction(),'action');
		$this->assertEquals($this->route->getMethod(),'method');
		$this->assertEquals($this->route->getParams(),array('param1','param2'));
	}
	
	// covers setters
	public function testSetters() {
		$this->route->setId('idb');
		$this->route->setAction('actionb');
		$this->route->setMethod('methodb');
		$this->route->setParams(array('param1b','param2b'));
		
		$this->assertEquals($this->route->getId(),'idb');
		$this->assertEquals($this->route->getAction(),'actionb');
		$this->assertEquals($this->route->getMethod(),'methodb');
		$this->assertEquals($this->route->getParams(),array('param1b','param2b'));
	}
	
	// covers is valid
	public function testIsValid() {
		
		$this->route->setId(null);		
		$this->assertFalse($this->route->isValid());
		$this->route->setId('id');
		
		$this->route->setAction(null);
		$this->assertFalse($this->route->isValid());
		$this->route->setAction('action');
		
		$this->route->setMethod(null);
		$this->assertFalse($this->route->isValid());
		$this->route->setMethod('method');
		
		
		$this->assertTrue($this->route->isValid());		
	}
}