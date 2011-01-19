<?php
/**
 * Loader class
 * 
 * Execute all the loading the application needs. It doesn't include the files : the symfony universal autoloader does it well.
 * This class works as an Abstract Factory.
 *
 * @package     GameManager
 * @subpackage  Loader
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

namespace GameManager\Core\Component;

use GameManager\Core\Exception\GameManagerEx;

use GameManager\Core\Component\Loader\ViewFactory;

use \GameManager\Core\Application;
use GameManager\Core\Component\Loader\ConfigFactory;
use GameManager\Core\Component\Loader\ObjectFactory;
use GameManager\Core\Component\Request;

class Loader {
	
	/**
	 * The type of element to load : library
	 * @staticvar string
	 */
	const T_LIBRARY = 'library';

	/**
	 * The type of element to load : action
	 * @staticvar string
	 */
	const T_ACTION = 'action';

	/**
	 * The type of element to load : config
	 * @staticvar string
	 */
	const T_CONFIG = 'config';
	
	/**
	 * The type of element to load : view
	 * @staticvar string
	 */
	const T_VIEW = 'view';
		
	/**
	 * The application object instance
	 * @var GameManager
	 * @access protected
	 */	
	protected $application = null;
	
	/**
	 * Load an item into the application.
	 * @param string $type the type of the element to load (@see constants)
	 * @param string $name
	 * @param array $param optional arrays of param
	 * @uses Loader::T_LIBRARY
	 * @uses Loader::T_CONFIG
	 * @uses Loader::T_ACTION
	 * @dispatches loader.object_loaded event when a 'something' is loaded
	 * @return mixed the loaded item
	 */
	function load($type,$name,array $params=null) {
				
		$factory = null;
		$loadedItem = null;
		
		switch($type) {				
			case self::T_CONFIG :
				$factory = new ConfigFactory($this->getApplication());
				$params = array('path'=>'game/configuration/');
				break;
			case self::T_LIBRARY:
				$factory = new ObjectFactory($this->getApplication());
				$params = array('namespace'=>'GameManager\Core\Library\\');
				break;
			case self::T_ACTION:
				$factory = new ObjectFactory($this->getApplication());
				$module = $this->getApplication()->getRequest()->getInformation(Request::REQUEST_MODULE);
				$params = array('namespace'=>'\Game\Module\\'.$module.'\Action\\');
				break;
			case self::T_VIEW:
				$factory = new ViewFactory($this->getApplication());
				$module = $this->getApplication()->getRequest()->getInformation(Request::REQUEST_MODULE);
				$params = array('module' => $module, 'data'=>$params);
				break;
		}
		
		if(is_null($factory))
			throw new RuntimeException('There is no type item matching with '.$type.'.');
			
		$loadedItem = $factory->process($name,$params);

		if(is_null($loadedItem))
			throw new GameManagerEx('Nothing has been loaded. Name: '.$name.' - Type:'.$type.'.');
			
		$this->getApplication()->getEventDispatcher()->notify(new \sfEvent($loadedItem,'loader.object_loaded'));

		return $loadedItem;
	}
	
	/**
	 * Gets the application object instance
	 * @return GameManager
 	 * @access protected
	 */
	protected function getApplication() {
		if(is_null($this->application))
			throw new RuntimeException('Loader::getApplication. An application object must be set to the Loader.');
			
		return $this->application;
	}
	
	/**
	 * Sets the application object instance
	 * @param GameManager $application
	 */
	public function setApplication(Application $application) {
		$this->application = $application;
	}	
}