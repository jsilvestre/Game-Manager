<?php

require_once '../../lib/core/Library.php';
require_once '../../lib/core/Loader.php';
require_once '../../lib/core/Collection.php';
require_once '../../lib/external/vendor/sfFinder.php';

spl_autoload_register(array('Loader','autoloadException'));
spl_autoload_register(array('Loader','autoloadExternal'));
spl_autoload_register(array('Loader','autoloadLibrary'));

class TestRouter extends PHPUnit_Framework_TestCase {
	
	protected $router;
	protected $request;
	protected $sourceArray;
	
	public function setUp() {
		$app = new GameManager(new sfEventDispatcher(), new Request(), new Response(),new Loader());
		$this->router = new Router($app);
		$this->request = new Request();
	}
	
	/**
	 * @dataProvider providerSourceArray
	 */
	public function testProcessRouting($source,$size,$requestType) {
		$this->router->setSourceArray($source);
		$this->router->processRouting($this->request);
		
		$this->assertEquals($this->request->requestSize(),$size);
		$this->assertEquals($this->request->getInformation(Request::REQUEST_TYPE),$requestType);
	}
	
	/**
	 * @dataProvider providerSourceArrayWrong
	 * @expectedException InvalidRouteEx
	 */
	public function testProcessRoutingErrorCase($source) {
		$this->router->setSourceArray($source);
		$this->router->processRouting($this->request);		
	}
	
	public function providerSourceArray() {
		return array(
			array(array('id' => 'action-method'),1,Router::T_COMPLETE_LOADING),
			array(array('id' => 'action-method',
						Router::U_REQUEST_TYPE => Router::T_PART_LOADING),1,Router::T_PART_LOADING),
			array(array('id' => 'action-method',
						Router::U_REQUEST_TYPE => Router::T_COMPLETE_LOADING),1,Router::T_COMPLETE_LOADING),
			array(array('id' => 'action-method-param1:param2'),1,Router::T_COMPLETE_LOADING),
			array(array('id' => 'action-method-param1'),1,Router::T_COMPLETE_LOADING),
			array(array('id' => 'action-method-param1:param2',
						'id2' => 'action-method-param1:param2'),2,Router::T_COMPLETE_LOADING)	
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
		$this->assertEquals(Router::makeURL($arrayURL),$url);
	}
	
	public function providerURL() {
		return array(
			array(array(array('id','action','method',array('param1','param2'))),Router::U_REQUEST_TYPE."=".Router::T_PART_LOADING."&id=action-method-param1:param2"),
			array(array(array('id','action','method')),Router::U_REQUEST_TYPE."=".Router::T_PART_LOADING."&id=action-method"),
			array(array(array('id','action','method',array('param1'))),Router::U_REQUEST_TYPE."=".Router::T_PART_LOADING."&id=action-method-param1"),
			array(array(array('id','action','method'),array('id2','actionb','methodc')),Router::U_REQUEST_TYPE."=".Router::T_PART_LOADING."&id=action-method&id2=actionb-methodc"),
		);				
	}
}