<?php

namespace GameManager\Test\Core\Library;

use \GameManager\Core\Component as Comp;
use \GameManager\Core\Library as Lib;

class TestSession extends \PHPUnit_Framework_TestCase {
	
	protected $session;
	
	public function setUp() {
		$app = new \GameManager\Core\Application(new \sfEventDispatcher(), new Comp\Request(), new Comp\Response(),new Comp\Loader());
		$this->session = new MockImplementSessionStorage($app);
	}
	
	public function testSetGetData() {
		
		$this->assertFalse($this->session->getData('id'));
		
		$this->session->setData('id', 'datatest');		
		$this->assertEquals('datatest',$this->session->getData('id'));
		
	}
	
	public function testFlashData() {
		
		$this->assertFalse($this->session->getFlashdata('id'));
		$this->session->setFlashData('id','datatest');
		$this->assertFalse($this->session->getFlashdata('id'));
		
		$this->session->manageFlashdata();		
		$this->assertEquals('datatest',$this->session->getFlashdata('id'));
		
		$this->session->manageFlashdata();		
		$this->assertFalse($this->session->getFlashdata('id'));
		
	}
	
	public function testUnsetData() {
		$this->session->setData('id', 'datatest');		
		$this->assertEquals('datatest',$this->session->getData('id'));
		$this->session->unsetData('id');
		$this->assertFalse($this->session->getData('id'));
	}
	
	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testUnsetDataErrorCase() {
		$this->session->unsetData('unexistantid');
	}
}

class MockImplementSessionStorage extends Lib\Session\SessionStorage {
	
	protected function serialize() {}
	protected function unserialize() {}
}