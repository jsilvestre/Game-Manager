<?php

namespace GameManager\Test\Core\Component;

use \GameManager\Core\Component as Comp;
use \GameManager\Core\Library as Lib;

class TestRequest extends \PHPUnit_Framework_TestCase {
	
	protected $request;
	
	public function setUp() {
		$this->request = new Comp\Request();
		$this->route = new Lib\Router\Route('id','action','method','param1:param2');
	}
	
	// covers add route, getAllRoute
	public function testAddRoute() {
			
		$this->request->addRoute($this->route);
		
		$this->assertEquals(count($this->request->getAllRoutes()),1);
		$this->assertEquals($this->request->getAllRoutes(),array($this->route->getId() => $this->route));
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testAddRouteErrorCase() {
		$this->request->addRoute("anything but no Route object");
	}
	
	public function testGetRoute() {
		$this->request->addRoute($this->route);
		
		$this->assertEquals($this->request->getRoute($this->route->getId()),$this->route);
	}
	
	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetRouteErrorCase() {
		$this->request->addRoute($this->route);
		
		$this->request->getRoute("nonExistentIndex");
	}

	public function testRequestSize() {
		$this->request->addRoute($this->route);
		
		$this->assertEquals($this->request->requestSize(),1);
	}
	
	public function testIsRequestEmpty() {
		
		$this->assertTrue($this->request->isRequestEmpty());
		
		$this->request->addRoute($this->route);
		
		$this->assertFalse($this->request->isRequestEmpty());		
	}
	
	// covers normal case for get and set information
	public function testSetInformation() {
		
		$this->request->setInformation(Comp\Request::USER_IP,'127.0.0.1');
		
		$this->assertEquals($this->request->getInformation(Comp\Request::USER_IP),'127.0.0.1');
	}
	
	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testSetInformationErrorCase() {
		
		$this->request->setInformation('randomstuff','127.0.0.1');
	}
	
	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetInformationErrorCase() {
		
		$this->request->getInformation('randomstuff','127.0.0.1');
	}
	
	// covers get all information
	public function testGetAllInformation() {
		$this->request->setInformation(Comp\Request::REQUEST_TYPE,'something');
		$this->request->setInformation(Comp\Request::USER_IP,'somethingelse');
		
		$this->assertEquals($this->request->getAllInformation(),array(Comp\Request::REQUEST_TYPE	=>'something',
																	  Comp\Request::USER_IP		=>'somethingelse'));
	}
	
	// covers set all information
	public function testSetAllInformation() {
		$info = array(Comp\Request::REQUEST_TYPE	=>'something',
					  Comp\Request::USER_IP		=>'somethingelse');
					  
		$this->request->setAllInformation($info);
		
		$this->assertEquals($this->request->getAllInformation(),$info);
	}
}