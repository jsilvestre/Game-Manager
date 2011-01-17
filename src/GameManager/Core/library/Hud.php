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
 * @todo must be design with the new xml+ dumper concept
 */

namespace GameManager\Core\Library;

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

		$elements = $xmlelements["interface"];
		
		foreach($elements as $element) { // let's parse the different attributes
			$id = (string) $element["id"]; 
			$defaut = $element->defaut;
			$action = (string) $defaut["action"];
			$method = (string) $defaut["method"];
		
			$defaultRoute = new Route($id,$action,$method); // we create the default route the HUD Element should load
		
			$hud_element = new HudElement($this,$id);
			$hud_element->setRoute($defaultRoute);
		
			$this->addElement($id,$hud_element); // we add the HudElement to the HudElements' array of the HUD		
		}
	}
	
	/**
	* Set the routes to the matching hud's elements
	* @param array $routes
	*/
	public function setRoutesToElements(array $routes) {

		// we only consider routes that can match to a Hud's element
		$routes = array_intersect_key($routes,$this->getAllElements());
	
		foreach($routes as $id => $v) {
			$this->getElement($id)->setRoute($v);
			$this->getElement($id)->setIsRouted(true);
		}		
	}	

	/**
	* Add a HUD element to the HUD
	* Throw WrongDatTypeEx, GameManagerEx
	*
	* @param $index
	* @return HudElement
	*/
	function addElement($index,HudElement $value) {
		if(!is_string($index))
			throw new WrongDataTypeEx("index",gettype($index),"string");
					
		if(array_key_exists($index,$this->_elements))
			throw new GameManagerEx("HUD::The HUD Element ID must be UNIQUE");
			
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
	 * Indicates if the action has been loaded yet or not.
	 * @param $actionName
	 * @return booolean
	 */	
	function isActionLoaded($actionName) {
		return $this->getContainer(Loader::T_ACTION)->offsetExists(strtolower($actionName));
	}
	
	/**
	* Generate the hud render. If there is no request, we display the whole interface otherwise we create XML to answer the request.
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