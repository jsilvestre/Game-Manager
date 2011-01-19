<?php
/**
 * Hud class.
 * 
 * The HUD manages the layout of the application.
 *
 * @package     GameManager
 * @subpackage	Hud
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @todo must be design with the new xml + dumper concept
 */

namespace GameManager\Core\Library;

use GameManager\Core\Component\Request;

use GameManager\Core\Application;
use GameManager\Core\Component\Loader;
use GameManager\Core\Library\Hud\HudElement;
use GameManager\Core\Library\Router;

class Hud extends Library implements \GameManager\Core\IDisplayObject {
	
	/**
	* Array of HudElements
	* @var array
	*/
	private $_elements;
	
	/**
	* Initialize the attributes by default
	*/
	function init() {
		$this->_elements = array();
	}

	/**
	* Create the HUD from the configuration
	*/
	function create() {
		
		$appConf = $this->getContainer(Loader::T_CONFIG)->offsetGet(Application::N_CONFIG_APP);
				
		foreach($appConf["interface"] as $element) { // let's parse the different attributes

			$id = $element['id'];
			$action = $element['target']['action'];
			$method = $element['target']['method'];
			$params = $element['target']['params'];
		
			$defaultRoute = new Router\Route($id,$action,$method,$params); // we create the default route the HUD Element should load
	
			$hud_element = new HudElement($this,$id);
			$hud_element->setRoute($defaultRoute);
		
			$this->addElement($id,$hud_element); // we add the HudElement to the HudElements' array of the HUD		
		}
	}
	
	/**
	* Set the routes to the matching hud elements
	*/
	public function setRoutesToElements() {

		$routes = $this->getRequest()->getAllRoutes();
		
		// we only consider routes that match to a Hud element
		$routes = array_intersect_key($routes,$this->getAllElements());
	
		foreach($routes as $id => $value) {
			$this->getElement($id)->setRoute($value);
			$this->getElement($id)->setIsRouted(true);
		}		
	}	

	/**
	* Add a HUD element to the HUD
	* @param $index
	* @return HudElement
	* @throws GameManagerEx if the $index is already the hud array
	*/
	function addElement($index,HudElement $value) {
					
		if(array_key_exists($index,$this->_elements))
			throw new \GameManager\Core\Exception\GameManagerEx("HUD::The HUD Element ID must be UNIQUE");
			
		$this->_elements[$index] = $value;
	}

	/**
	* Get the HUD elements array
	* @return array
	*/
	public function getAllElements() {
		return $this->_elements;
	}
	
	/**
	* Get a HUD element
	* @param $index
	* @return HudElement
	* @throws OutOfBoundsException if the $index is not in the array
	*/
	public function getElement($index) {
		
		if(!array_key_exists($index,$this->_elements))
			throw new OutOfBoundsException("Index ".$index." is not in array _elements.");
		
		return $this->_elements[$index];
	}
	
	/**
	* Generate the hud render.
	* @return string the type of answer : complete or part.
	*/
	function render() {

		// if the request type is a part loading, we create the XML containing the answer
		if($this->getRequest()->getInformation(Request::REQUEST_TYPE)==Router::T_PART_LOADING) {
			$this->getResponse()->addHeader("Content-Type: text/xml");			
			$this->getResponse()->addContent($this->_partRender());

			$render = Router::T_PART_LOADING;
		}
		else { // otherwise, we render the whole page
			$this->getResponse()->addContent($this->_completeRender());
			
			$render = Router::T_COMPLETE_LOADING;
		}
		
		return $rendu;
	}
	
	/**
	 *  Generate the XML to answer the request
	 *  @return string
	 *  @access private
	 */
	private function _partRender() {
		
		$rendu='<?xml version="1.0"?><response>'; 
		
		foreach($this->getAllElements() as $element) {
			if($element->isRouted()) {
				$rendu.= '<element id="'.$element->getId().'">'.$element->render().'</element>';
			}
		}
		
		$rendu.="</response>";

		return $rendu;
	}
	
	/**
	 * Generate the whole interface
	 * @return string
	 * @access private
	 */
	private function _completeRender() {
		
		$render = "";

		$hudElementsRender = $this->_getHudElementsRender();
		
		/*$rendu = GameManager::getInstance()->load->view("base/layout/header_view",$header);

		$url = array (array('detection','sample','test'),array('infos_perso','sample','test'));
		$url2 = array (array('detection','sample','index'),array('infos_perso','sample','index'));
		$rendu.= '<p>'.anchor("MyText",$url).' - '.anchor("MyText2",$url2).'</p>';
		
		$rendu .= GameManager::getInstance()->load->view("base/layout/hud_view",$hudElementsRender);
		$rendu .= GameManager::getInstance()->load->view("base/layout/footer_view");*/

		return $render;
	}
	
	/**
	 * Gets the hud elements array after rendering
	 * @return array
	 * @access private
	 */
	private function _getHudElementsRender() {
		$hudElementsRender = array();
			
		foreach($this->getAllElements() as $element) {
				$data = array (
					"element_id" => $element->getId(),
					"element_render" => $element->render()
				);
				
				$hudElementsRender[$element->getId()] = "";
				//$hudElementsRender[$element->getId()] = GameManager::getInstance()->load->view("base/framework/hudElement_view",$data);
		}

		return $hudElementsRender;
	}
}