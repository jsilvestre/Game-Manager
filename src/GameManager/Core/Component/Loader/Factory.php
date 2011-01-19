<?php
/**
 * Factory class.
 * Abstracts item factories.
 *
 * @package     GameManager
 * @subpackage  Loader
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

namespace GameManager\Core\Component\Loader;

abstract class Factory {
	
	/**
	 * Process the factorying.
	 * @param string $name the item name.
	 * @param array $params array of optional parameters. 
	 * @return array like this: array('index'=>$index,'value'=>$value) 
	 */
	abstract public function process($name,array $params=null);
}