<?php
/**
 * Object Factory.
 * Creates all the loadable instance : libraries, actions.
 *
 * @package     GameManager
 * @subpackage  Loader
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @todo this class needs a strong documentation :)
 */

namespace GameManager\Core\Component\Loader;

use GameManager\Core\Application;

class ObjectFactory {
		
	protected $application;
	
	function __construct(Application $app) {
		$this->application = $app;
	}
	
	public function process($name,$namespace) {
		
		$indexName = strtolower($name);	
		
		$refl = new \ReflectionClass($namespace.$name); // the include is made by autoloading
		
		$instance = $refl->newInstance($this->application);
		
		//$this->application->getContainer($type)->offsetSet($indexName,$instance);
		return array(
					'index'	=> $indexName,
					'value'	=> $instance
				);			
	}
	
	
	
}