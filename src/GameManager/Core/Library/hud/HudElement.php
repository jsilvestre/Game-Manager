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

use \GameManager\Core\Library\Hud;
use \GameManager\Core\Library\Router\Route;
use \GameManager\Core\Exception\GameManagerEx;
use \GameManager\Core\Component\Loader;

class HudElement implements \GameManager\Core\IDisplayObject {
	
	/**
	* HudElement route
	* @var Route
	* @access private
	*/
	private $_route = null;

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
	 * The action container of the application
	 * @var Collection of Action
	 */
	private $_actionContainer;

	/**
	* Construct the object
	*/
	function __construct(Hud $hud,$id,\GameManager\Core\Component\Collection $actionContainer) {
		$this->setHud($hud);
		$this->_actionContainer = $actionContainer;
		$this->init($id);
	}
	
	/**
	* Initialize the object attributes
	*/
	function init($id) {
		$this->setId($id);
		$this->setRoute(new Route(null, null, null)); // a unvalid route will set the parameter at null
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
	* Set the HudElement route. If the route is not valid, we set the attribute to null. Damn you PHP fake type hint.
	* @param Route $route
	*/
	function setRoute(Route $route) {

		if(!$route->isValid())
			$this->_route = null;
		else		
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
		
		if(!$this->_actionContainer->offsetExists($action))
			$loadedItem = $this->getHud()->load(Loader::T_ACTION,$action);
		else
			$loadedItem = $this->_actionContainer->offsetGet($action);
		
		// we catch the possible Exception and turn it into GameManagerEx
		try {
			$refl = new \ReflectionMethod($loadedItem,$this->getRoute()->getMethod());
			
			// get the array (index,value) given by the method
			$render = $refl->invokeArgs($this->_actionContainer->offsetGet($action),$this->getRoute()->getParams());
		}
		catch(Exception $e) {
			throw new GameManagerEx("Class ".$action." : can't find method ".$this->getRoute()->getMethod()." or the method is inaccessible.");			
		}
			
		if(empty($render))
			throw new GameManagerEx("The method ".$action.":".$this->getRoute()->getMethod()." doesn't return any result.");

		return $render;
	}
}