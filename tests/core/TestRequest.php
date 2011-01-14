<?php

require_once '../../lib/core/Library.php';
require_once '../../lib/core/Loader.php';
require_once '../../lib/core/Collection.php';
require_once '../../lib/external/vendor/sfFinder.php';

spl_autoload_register(array('Loader','autoloadException'));
spl_autoload_register(array('Loader','autoloadExternal'));
spl_autoload_register(array('Loader','autoloadLibrary'));

class TestRequest extends PHPUnit_Framework_TestCase {
	
	protected $request;
	
	public function setUp() {
		$this->request = new Request();
		$this->route = new Route('id','action','method','param1:param2');
	}
	
	// covers add route, getAllRoute
	public function testAddRoute() {
			
		$this->request->addRoute($this->route);
		
		$this->assertEquals(count($this->request->getAllRoutes()),1);
		$this->assertEquals($this->request->getAllRoutes(),array($this->route->getId() => $this->route));
	}
	
	/**
	 * @expectedException ErrorException
	 */
	public function testAddRouteErrorCase() {
		//$this->request->addRoute("anything but no Route object");
		throw new ErrorException();
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
		
		$this->request->setInformation(Request::USER_IP,'127.0.0.1');
		
		$this->assertEquals($this->request->getInformation(Request::USER_IP),'127.0.0.1');
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
		$this->request->setInformation(Request::REQUEST_TYPE,'something');
		$this->request->setInformation(Request::USER_IP,'somethingelse');
		
		$this->assertEquals($this->request->getAllInformation(),array(Request::REQUEST_TYPE	=>'something',
																	  Request::USER_IP		=>'somethingelse'));
	}
	
	// covers set all information
	public function testSetAllInformation() {
		$info = array(Request::REQUEST_TYPE	=>'something',
					  Request::USER_IP		=>'somethingelse');
					  
		$this->request->setAllInformation($info);
		
		$this->assertEquals($this->request->getAllInformation(),$info);
	}
}