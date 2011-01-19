<?php
/**
 * Object Factory.
 * Creates all the loadable instance : ApplicationElement.
 *
 * @package     GameManager
 * @subpackage  Loader
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

namespace GameManager\Core\Component\Loader;

use GameManager\Core\Application;
use GameManager\Core\ApplicationElement;

class ObjectFactory {
	
	/**
	 * The application instance
	 * @var Application
	 */
	protected $application;
	
	/**
	 * Builds the object
	 * @param Application $app
	 */
	function __construct(Application $app) {
		$this->application = $app;
	}
	
	/**
	 * @inheritdoc
	 * @throws \Exception if the class that we want to instantiate is not an ApplicationElement class.
	 */
	public function process($name,array $params=null) {
		
		$indexName = strtolower($name);
		
		$name = strtoupper($name[0]).substr($name,1);
		
		$refl = new \ReflectionClass($params['namespace'].$name); // the include is made by autoloading
		
		if(!$refl->isSubclassOf('\GameManager\Core\ApplicationElement'))
			throw new \Exception("You can only load ApplicationElement with the ObjectFactory.");
		
		$instance = $refl->newInstance($this->application);
		
		return $instance;
	}	
}