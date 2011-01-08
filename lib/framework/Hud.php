<?php

/**
 * Hud class. Manage the layout of the application
 *
 * @package     GameManager
 * @subpackage	Hud
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class Hud extends Library implements IDisplayObject {
	
	/**
	* Array of HudElements
	*/
	private $_elements;
	
	/**
	* Array of instances of loaded actions
	*/
	private $_actions;
	
	/**
	* Initialize the attributes by default
	*/
	function init() {
		$this->_elements = array();
		$this->_actions = array();
		$this->_loadedActions = array();
	}

	/**
	* Create the HUD from the descriptor file
	*
	* @param $fileName
	*/
	function create($file) {
	
		$path = GameManager::getPath().'game/ressources/'.$file;
	
		if(!file_exists($path))
			throw new FileNotFoundEx($path);
	
		$xml = simplexml_load_file($path); // we load the XML file
		$xmlelements = get_object_vars($xml);

		foreach($xmlelements["element"] as $element) { // let's parse the differents attribute
			$id = (string) $element["id"]; 
			$defaut = $element->defaut;
			$action = (string) $defaut["action"];
			$method = (string) $defaut["method"];
		
			$defaultRoute = new Route($id,$action,$method); // we create the default route the HUD Element should load
		
			$hud_element = new HudElement($this,$id);
			$hud_element->setRoute($defaultRoute);
		
			$this->setElement($id,$hud_element); // we add the HudElement to the HudElements' array of the HUD		
		}
	
		//$this->setDisplayAll(true);
	}
	
	/**
	* Set the routes to the matching hud's elements
	* Throw WrongDataTypeEx.
	*
	* @param $routes
	*/
	function setRoutesToElements($routes) {
		
		if(!is_array($routes))
			throw new WrongDataTypeEx("routes",gettype($routes),"array");

		// we only consider routes that can match to a Hud's element
		$routes = array_intersect_key($routes,$this->_elements);

		/*if(count($routes)>0) {
			foreach($this->getAllElements() as $hudElement) {
				$hudElement->setIsRouted(false);
			}
			
			$this->setDisplayAll(false);
		}*/
	
		foreach($routes as $id => $v) {
			$this->getElement($id)->setRoute($v);
			$this->getElement($id)->setIsRouted(true);
		}		
	}	

	/**
	* Set a HUD element
	* Throw WrongDatTypeEx, GameManagerEx
	*
	* @param $index
	* @return HudElement
	*/
	function setElement($index,$value) {
		if(!is_string($index))
			throw new WrongDataTypeEx("index",gettype($index),"string");
		
		if(!($value instanceof HudElement))
			throw new WrongDataTypeEx("value",gettype($value),"HudElement");
			
		if(array_key_exists($index,$this->_elements))
			throw new GameManagerEx("HUD::The HUD Element ID must be UNIQUE");
			
		$this->_elements[$index] = $value;
	}

	/**
	* Get the HUD elements' array
	*
	* @return array
	*/
	function getAllElements() {
		return $this->_elements;
	}
	
	/**
	* Get a HUD element
	* Throw NonExistentDataEx
	*
	* @param $index
	* @return HudElement
	*/
	function getElement($index) {
		
		if(!array_key_exists($index,$this->_elements))
			throw new NonExistentDataEx(NonExistentDataEx::CLASSIC,$index);
		
		return $this->_elements[$index];
	}

	/**
	* Add an action instance to the action array of the HUD. This method should only be called by the Loader class.
	* Throw WrongDataTypeEx
	*
	* @param $actionName
	* @param $actionInstance
	*/
	function addAction($actionName, $actionInstance) {
		if(!is_string($actionName))
			throw new WrongDataTypeEx("actionName",gettype($actionName),"string");
		
		if(!($actionInstance instanceof Action))
			throw new WrongDataTypeEx("actionInstance",gettype($actionInstance),"Action");
			
		$this->_actions[$actionName] = $actionInstance;
	}
	
	/**
	 * Get back the instance of the requested action. 
	 * Throw a ActionNotLoadedEx if the action class has not been loaded yet.
	 * 
	 * @param $actionName
	 * @return Action
	 */	
	function action($actionName) {
		if(!array_key_exists($actionName,$this->_actions))
			throw new ActionNotLoadedEx($actionName);
			
		return $this->_actions[$actionName];		
	}
	
	/**
	* Alias for the action's loading method in the Loader class
	*
	* @param $actionName
	*/
	function loadAction($actionName) {
		GameManager::getInstance()->load->action($actionName);
	}
	
	/**
	 * Indicates if the action has been loaded yet or not.
	 * 
	 * @param $actionName
	 * @return booolean
	 */	
	function isActionLoaded($actionName) {
		return array_key_exists(strtolower($actionName),$this->_actions);
	}
	
	/**
	* Generate the hud's render. If there is no request, we display the whole interface otherwise we create XML to answer the request.
	*
	* @return string
	*/
	function render() {
		$rendu = "";

		// if the request type is a part loading, we create the XML containing the answer
		if(!Router::isCompleteLoading()) {
			
			header("Content-Type: text/xml");		
			
			$rendu.='<?xml version="1.0"?><answer>'; 
			
			foreach($this->getAllElements() as $element) {
				if($element->isRouted()) {
					$rendu.= '<element id="'.$element->getId().'">'.$element->render().'</element>';
				}
			}
			
			$rendu.="</answer>";
		
		}
		else { // otherwise, we render the whole page
			$hud_elements = array();
			
			foreach($this->getAllElements() as $element) {
					$data = array (
						"element_id" => $element->getId(),
						"element_rendu" => $element->render()
					);

					$hud_elements[$element->getId()] = GameManager::getInstance()->load->view("base/framework/hudElement_view",$data);
			}
			
			$header = array(
				'css' => GameManager::getInstance()->library('configuration')->getCss(),			
				'javascript' => GameManager::getInstance()->library('configuration')->getJavascript()
			);
			
			$header = array_merge(GameManager::getInstance()->library('configuration')->get('base'),$header);
			
			$rendu = GameManager::getInstance()->load->view("base/layout/header_view",$header);

			$url = array (array('detection','sample','test'),array('infos_perso','sample','test'));
			$url2 = array (array('detection','sample','index'),array('infos_perso','sample','index'));
			$rendu.= '<p>'.anchor("MyText",$url).' - '.anchor("MyText2",$url2).'</p>';
			
			$rendu .= GameManager::getInstance()->load->view("base/layout/hud_view",$hud_elements);
			$rendu .= GameManager::getInstance()->load->view("base/layout/footer_view");
		}
		
		return $rendu;
	}
}