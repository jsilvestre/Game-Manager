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
 * @todo this class needs a strong documentation :)
 */

namespace GameManager\Core\Component;

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
	 * Load an element to the application.
	 * @param string $type the type of the element to load (@see constants)
	 * @param string $name
	 * @param array $param optional arrays of param
	 * @uses Loader::T_LIBRARY
	 * @uses Loader::T_CONFIG
	 * @uses Loader::T_ACTION
	 * @dispatches loader.object_loaded event when a 'something' is loaded
	 */
	function load($type,$name,array $param=null) {
		
		if(is_array($name)) {
			foreach($name as $unit)
				$this->load($type,$unit);
		}	
		else {
			$loadedObject = null;
			
			switch($type) {				
				case self::T_CONFIG :
					$factory = new ConfigFactory($this->getApplication());
					$loadedObject = $factory->process($name,'game/configuration/');
					break;
				case self::T_LIBRARY:
					$factory = new ObjectFactory($this->getApplication());
					$loadedObject = $factory->process($name,'GameManager\Core\Library\\');
					break;
				case self::T_ACTION:
					$factory = new ObjectFactory($this->getApplication());
					$module = $this->getApplication()->getRequest()->getInformation(Request::REQUEST_MODULE);
					$loadedObject = $factory->process($name,'Module\\'.$module.'\Action\\');
					break;
				case self::T_VIEW:
					echo "load view";
					break;
			}

			if(!is_null($loadedObject)) {
				$this->getApplication()->getContainer($type)->offsetSet($loadedObject['index'],$loadedObject['value']);
				$this->getApplication()->getEventDispatcher()->notify(new \sfEvent($loadedObject,'loader.object_loaded'));
			}
		}
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