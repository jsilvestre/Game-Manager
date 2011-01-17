<?php

/**
 * Represents a hud element
 *
 * @package     GameManager
 * @subpackage	Hud
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

namespace GameManager\Core\Library\Hud;

class HudElement implements \GameManager\Core\IDisplayObject {
	
	/**
	* HudElement route
	* @var Route
	* @access private
	*/
	private $_route;

	/**
	* The bound hud
	* @var Hud
	* @access private
	*/
	private $_hud;
	
	/**
	* HudElement ID
	*/
	private $_id;
	
	/**
	* Indicates if the hud element has been routed by a URL request
	*/
	private $_isRouted;

	/**
	* Construct the object
	*/
	function __construct(Hud $hud,$id) {
		$this->setHud($hud);		
		$this->init($id);
	}
	
	/**
	* Initialize the object attributes
	*/
	function init($id) {
		$this->setId($id);
		$this->setRoute(null);
		$this->setIsRouted(false);
	}
	
	/**
	* Set the HudElement ID
	* @param string $id
	*/
	function setId($id) {			
		$this->_id = $id;
	}
	
	/**
	* Get the HudElement ID
	* @return string
	*/
	function getId() {
		return $this->_id;
	}

	/**
	* Set the HudElement HUD
	* @param Hud $hud
	*/
	function setHud(Hud $hud) {		
		$this->_hud = $hud;
	}
	
	/**
	* Get the HudElement HUD
	* @return Hud
	*/
	function getHud() {
		return $this->_hud;
	}

	/**
	* Set the HudElement route. The $route parameter can be NULL (if the HudElement has no Route)
	* @param Route $route
	*/
	function setRoute(Route $route) {			
		$this->_route = $route;
	}
	
	/**
	* Get the HudElement route
	*
	* @return Route
	*/
	function getRoute() {
		return $this->_route;
	}
	
	/**
	* Indicates if the HudElement has a route and if it is a valid one.
	* @return boolean
	*/
	function hasValidRoute() {
		return ($this->getRoute()!=NULL && $this->getRoute()->isValid());
	}
	
	/**
	* Set the attribute isRouted
	* @param bool $value
	*/
	function setIsRouted($value) {			
		$this->_isRouted = $value;
	}
	
	/**
	* Get the attribute isRouted
	* @return bool
	*/
	function isRouted() {
		return $this->_isRouted;
	}
	
	/**
	* Generate a render for the HudElement
	* @return string
	*/
	function render() {
	
		$action = $this->getRoute()->getAction();	
		
		if(empty($action)) // empty deals tests isset to
			throw new InvalidRouteEx($this->getId(),$this->getRoute());
		
		if(!$this->getHud()->isActionLoaded($action))
			$this->getHud()->load(Loader::T_ACTION,$action);
		
		// we catch the possible Exception and turn it into GameManagerEx
		try {
			$refl = new ReflectionMethod($action,$this->getRoute()->getMethod());
			
			$render = $refl->invokeArgs($this->getHud()->getContainer(Loader::T_ACTION)->offsetGet($action),$this->getRoute()->getParams());
		}
		catch(Exception $e) {
			throw new GameManagerEx("Class ".$action." : can't find method ".$this->getRoute()->getMethod()." or the method is inaccessible.");			
		}
			
		if(!(isset($render)))
			throw new GameManagerEx("The method ".$action.":".$this->getRoute()->getMethod()." doesn't return any result.");

		return $render;
	}
}