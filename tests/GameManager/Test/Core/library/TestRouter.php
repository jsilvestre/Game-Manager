<?php

namespace GameManager\Test\Core\Library;

use \GameManager\Core\Component as Comp;
use \GameManager\Core\Library as Lib;

class TestRouter extends \PHPUnit_Framework_TestCase {
	
	protected $router;
	protected $application;
	protected $sourceArray;
	
	public function setUp() {
		$this->application = new \GameManager\Core\Application(new \sfEventDispatcher(), new Comp\Request(), new Comp\Response(),new Comp\Loader());
		$this->router = new Lib\Router($this->application);
	}
	
	/**
	 * @dataProvider providerSourceArray
	 */
	public function testProcessRouting($source,$size,$requestType) {
		$this->router->setSourceArray($source);
		$this->router->processInformation();
		$this->router->processRouting();
		
		$this->assertEquals($this->application->getRequest()->requestSize(),$size);
		$this->assertEquals($this->application->getRequest()->getInformation(Comp\Request::REQUEST_TYPE),$requestType);
	}
	
	/**
	 * @dataProvider providerSourceArrayWrong
	 * @expectedException \GameManager\Core\Exception\InvalidRouteEx
	 */
	public function testProcessRoutingErrorCase($source) {
		$this->router->setSourceArray($source);
		$this->router->processRouting();		
	}
	
	public function providerSourceArray() {
		return array(
			array(array('id' => 'action-method'),1,Lib\Router::T_COMPLETE_LOADING),
			array(array('id' => 'action-method',
						Lib\Router::U_REQUEST_TYPE => Lib\Router::T_PART_LOADING),1,Lib\Router::T_PART_LOADING),
			array(array('id' => 'action-method',
						Lib\Router::U_REQUEST_TYPE => Lib\Router::T_COMPLETE_LOADING),1,Lib\Router::T_COMPLETE_LOADING),
			array(array('id' => 'action-method-param1:param2'),1,Lib\Router::T_COMPLETE_LOADING),
			array(array('id' => 'action-method-param1'),1,Lib\Router::T_COMPLETE_LOADING),
			array(array('id' => 'action-method-param1:param2',
						'id2' => 'action-method-param1:param2'),2,Lib\Router::T_COMPLETE_LOADING)	
		);
	}
	
	public function providerSourceArrayWrong() {
		return array(
			array(array('id' => 'action-method-')),
			array(array('id' => 'action-method-param1:')),
			array(array('id' => 'action1-method')),
			array(array('id' => 'action-method2'))
		);		
	}
	
	/**
	 * @dataProvider providerURL
	 */
	public function testMakeURL($arrayURL,$url) {
		$this->assertEquals(Lib\Router::makeURL($arrayURL),$url);
	}
	
	public function providerURL() {
		return array(
			array(array(array('id','action','method',array('param1','param2'))),Lib\Router::U_REQUEST_TYPE."=".Lib\Router::T_PART_LOADING."&id=action-method-param1:param2"),
			array(array(array('id','action','method')),Lib\Router::U_REQUEST_TYPE."=".Lib\Router::T_PART_LOADING."&id=action-method"),
			array(array(array('id','action','method',array('param1'))),Lib\Router::U_REQUEST_TYPE."=".Lib\Router::T_PART_LOADING."&id=action-method-param1"),
			array(array(array('id','action','method'),array('id2','actionb','methodc')),Lib\Router::U_REQUEST_TYPE."=".Lib\Router::T_PART_LOADING."&id=action-method&id2=actionb-methodc"),
		);				
	}
}