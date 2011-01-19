<?php

/**
 * View Factory.
 * Load the views.
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
use GameManager\Core\Component\Loader;
use GameManager\Core\Exception\FileNotFoundEx;

class ViewFactory extends Factory {
	
	/**
	 * The suffix of the view file name
	 * @staticvar
	 */
	const SUFFIX_NAME = "_view";
	
	/**
	 * @inheritdoc
	 * @throws InvalidArgumentException when the param argument is not an array
	 * @throws FileNotFoundEx if the view is not found
	 */
	public function process($name,array $params=null) {
			
		if(!is_array($params))
			throw new InvalidArgumentException("Unexpected type for var \$params. ".gettype($params)." given, array expeted.");
						
		$folder = Application::getPath()."game/module/".$params['module']."/view/";
		
		$path = $folder.$name.self::SUFFIX_NAME.'.php';
		
		if(!file_exists($path))
			throw new FileNotFoundEx($path);

		if(!is_null($params['data']))
			extract($params['data'],EXTR_OVERWRITE);	// we transform the array of parameters into variables
		
		// we buffer the view and get its content then we clean the buffer
		ob_start();
			require($path);
			$content = ob_get_contents();		
		ob_end_clean();
		
		return $content;
	}
}