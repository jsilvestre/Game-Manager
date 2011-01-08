<?php

/**
 * Exception class of the framework.
 *
 * @package     GameManager
 * @subpackage  Exception
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 */

class GameManagerEx extends Exception implements IDisplayObject {
		
	/**
	* Generate the render of an exception according to the application environment
	*
	*/
	function render() {

		$render = "";

		if(ENV_DEVELOPER) {
			$data = array (
				"message"	=> $this->getMessage(),
				"file"		=> $this->getFile(),
				"line"		=> $this->getLine(),
				"trace"		=> str_replace('#','<br />',$this->getTraceAsString()),
				"completeLoading"	=> Router::isCompleteLoading()		
			);
			
			$render = GameManager::getInstance()->load->view('base/framework/exception_dev_view',$data);
		}
		else {
			$data = array (
				"completeLoading" => Router::isCompleteLoading()
			);
			
			$render = GameManager::getInstance()->load->view('base/framework/exception_prod_view',$data);
		}
		
		return $render;		
	}
	
	/**
	* Display the generated content
	*
	*/
	function display() {
		echo $this->render();
	}
}



?>