<?php

/**
 * HudElement class. Represents a hud element
 *
 * @package     GameManager
 * @subpackage	Hud
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class HudElement implements IDisplayObject {
	
	/**
	* HudElement's route
	*/
	private $_route;

	/**
	* The bound hud
	*/
	private $_hud;
	
	/**
	* HudElement's ID
	*/
	private $_id;
	
	/**
	* Indicates if the hud's element has been routed by a URL request
	*/
	private $_isRouted;

	/**
	* Construct the object
	* Throw WrongDataTypeEx
	*/
	function __construct($hud,$id) {
		$this->setHud($hud);		
		$this->init($id);
	}
	
	/**
	* Initialize the object's attributes
	*/
	function init($id) {
		$this->setId($id);
		$this->setRoute(NULL);
		$this->setIsRouted(false);
	}
	
	/**
	* Set the HudElement's ID
	* Throw WrongDataTypEx.
	*
	* @param $id
	*/
	function setId($id) {
		if(!is_string($id))
			throw new WrongDataTypeEx("id",gettype($id),"string");
			
		$this->_id = $id;
	}
	
	/**
	* Get the HudElement's ID
	*
	* @return string
	*/
	function getId() {
		return $this->_id;
	}

	/**
	* Set the HudElement's HUD
	* Throw WrongDataTypEx.
	*
	* @param $hud
	*/
	function setHud($hud) {
		if(!($hud instanceof Hud))
			throw new WrongDataTypeEx("hud",gettype($hud),"Hud");
			
		$this->_hud = $hud;
	}
	
	/**
	* Get the HudElement's HUD
	*
	* @return Hud
	*/
	function getHud() {
		return $this->_hud;
	}

	/**
	* Set the HudElement's route. The $route parameter can be NULL (if the HudElement has no Route)
	* Throw WrongDataTypEx.
	*
	* @param $route
	*/
	function setRoute($route) {
		if(!($route instanceof Route) && !is_null($route))
			throw new WrongDataTypeEx("route",gettype($route),"Route");
			
		$this->_route = $route;
	}
	
	/**
	* Get the HudElement's route
	*
	* @return Route
	*/
	function getRoute() {
		return $this->_route;
	}
	
	/**
	* Indicates if the HudElement has a route and if it is a valid one.
	*
	* @return boolean
	*/
	function hasValidRoute() {
		return ($this->getRoute()!=NULL && $this->getRoute()->isValid());
	}
	
	/**
	* Set the attribute isRouted
	* Throw WrongDataTypeEx
	*
	* @param $value
	*/
	function setIsRouted($value) {
		if(!is_bool($value))
			throw new WrongDataTypeEx("value",gettype($value),"boolean");
			
		$this->_isRouted = $value;
	}
	
	/**
	* Get the attribute isRouted
	*
	* @return boolean
	*/
	function isRouted() {
		return $this->_isRouted;
	}
	
	/**
	* Generate a render for the HudElement
	*
	* @return string
	*/
	function render() {
	
		$action = $this->getRoute()->getAction();	
		
		if(!(isset($action) && !empty($action)))
			throw new InvalidRouteEx($this->getId(),$this->getRoute());
		
		$hud = $this->getHud();

		if(!$hud->isActionLoaded($action))
			$hud->loadAction($action);
		
		// we catch the possible Exception and turn it into GameManagerEx
		try {
			$refl = new ReflectionMethod($action,$this->getRoute()->getMethod());
			
			$render =$refl->invokeArgs($hud->action($action),$this->getRoute()->getParams());
		}
		catch(Exception $e) {
			throw new GameManagerEx("Class ".$action." : can't find method ".$this->getRoute()->getMethod()." or the method is inaccessible.");			
		}
			
		if(!(isset($render)))
			throw new GameManagerEx("The method doesn't return any result.");

		return $render;
	}
}