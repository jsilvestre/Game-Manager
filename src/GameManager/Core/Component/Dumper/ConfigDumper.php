<?php

/**
 * Dumper for the config file.
 * The configuration dumper transforms the configuration XML file into a configuration PHP file.
 *
 * @package     GameManager
 * @subpackage  Dumper
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @abstract
 * @todo this class needs to be refactored to clean a bit the code. We can make some methods more generic too.
 */

namespace GameManager\Core\Component\Dumper;

use GameManager\Core\Exception\GameManagerEx;

use \GameManager\Core\Component\Dumper;

class ConfigDumper extends Dumper {
	
	protected $path;
	
	/**
	 * Builds the object
	 * @param unknown_type $path
	 */
	public function __construct($path) {
		$this->path = $path;
	}
	
	/**
	 * Gets the path
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * @inheritdoc
	 */
	public function dump() {
		
		if(!file_exists($this->getPath().'.xml'))
			throw new \GameManager\Core\Exception\GameManagerEx('The config file application.xml does not exist.');
		
		if(file_exists($this->getPath().'.php'))
			unlink($this->getPath().'.php');
			
		$file = fopen($this->getPath().'.php','a');
		
		$result = '<?php'.PHP_EOL;
		
		// start XML parsing
		$xml = simplexml_load_file($this->getPath().'.xml'); // we load the XML file
		$xmlelements = get_object_vars($xml);

		$modules = array();
		
		foreach($xmlelements as $module) {
			
			$modules[] = $module['name'];
			
			$result.= PHP_EOL."/*".PHP_EOL." * Start ".$module['name']." configuration.".PHP_EOL." */".PHP_EOL;
			
			$result.= $this->_dumpInformation($module->information);
			$result.= $this->_dumpCss($module->css);
			$result.= $this->_dumpJs($module->javascript);
			$result.= $this->_dumpInterface($module->interface);
			
			if(isset($module['default']))
				$default = "'default' => ".$module['default'];
			else
				$default = "'default' => false";
			
			$result.= PHP_EOL."\$".$module['name']." = array(";
			$result.= PHP_EOL."\t'id' => '".$module['name']."',";
			$result.= PHP_EOL."\t'information' => \$information,";
			$result.= PHP_EOL."\t'css' => \$css,";
			$result.= PHP_EOL."\t'js' => \$javascript,";
			$result.= PHP_EOL."\t'interface' => \$interface,";
			$result.= PHP_EOL."\t".$default;
			$result.= PHP_EOL.");";
			
			$result.=PHP_EOL."/*".PHP_EOL." * End ".$module['name']." configuration.".PHP_EOL." */".PHP_EOL;
		}
		
		// end XML parsing		
		
		// we create the final array
		$result.= PHP_EOL."\$application = array(";
		
		foreach($modules as $module) {
			$result.= PHP_EOL."\t'".$module."' => \$".$module.",";
		}
		
		$result = self::removeLastChar($result, array(','));
		
		$result.= PHP_EOL.");";		
		$result.= PHP_EOL.'?>';
		
		fputs($file,$result);

		fclose($file);		
	}
	
	/**
	 * Handles the dumping of the part information
	 * @param \SimpleXMLElement $xmlObject
	 * @throws GameManagerEx if the collection meta is missing
	 * @return string
	 * @static private
	 */
	private function _dumpInformation(\SimpleXMLElement $xmlObject) {
		
		$result = "\$information = array(";
		
		if(isset($xmlObject->meta)) {
						
			$result.= PHP_EOL."\t'meta' => array(";
						
			foreach($xmlObject->meta->tag as $tag) {
				$result.= PHP_EOL."\t\t'".(string) $tag['type']."' => '".(string) $tag['value']."',";
			}
			
			$result = self::removeLastChar($result, array(','));
			
			$result.= PHP_EOL."\t)";
		}
		else
			throw new GameManagerEx('collection meta missing in information.');
		
		if(isset($xmlObject->misc)) {
			$result.= ",".PHP_EOL."\t'misc' => array(";
			
			foreach($xmlObject->misc->tag as $tag) {
				$result.= PHP_EOL."\t\t'".(string) $tag['type']."' => '".(string) $tag['value']."',";
			}

			$result = self::removeLastChar($result, array(','));
			
			$result.= PHP_EOL."\t)";
		}
		
		$result.= PHP_EOL.");";
		
		return $result;
	}
	
	/**
	 * Handles the dumping of the part CSS
	 * @param \SimpleXMLElement $xmlObject
	 * @return string
	 * @static private
	 */
	private function _dumpCss(\SimpleXMLElement $xmlObject) {
		
		$result = PHP_EOL."\$css = array(";
		
		$result.= PHP_EOL."\t'src' => '".$xmlObject['src']."',";
		$result.= PHP_EOL."\t'files' => array(";
		
		foreach($xmlObject->file as $file) {
			$result.= "'".(string) $file['name']."',";
		}
		
		$result = self::removeLastChar($result, array(','));
		
		$result.= ")".PHP_EOL.");";	
		
		return $result;
	}
	
	/**
	 * Handles the dumping of the part JS
	 * @param \SimpleXMLElement $xmlObject
	 * @return string
	 * @static private
	 */
	private function _dumpJs(\SimpleXMLElement $xmlObject) {
		
		$result = PHP_EOL."\$javascript = array(";
		
		$result.= PHP_EOL."\t'src' => '".$xmlObject['src']."',";
		$result.= PHP_EOL."\t'files' => array(";
		
		foreach($xmlObject->file as $file) {
			$result.= "'".(string) $file['name']."',";
		}
		
		$result = self::removeLastChar($result, array(','));
		
		$result.= ")".PHP_EOL.");";
		
		
		return $result;		
	}
	
	/**
	 * Handles the dumping of the part interface
	 * @param \SimpleXMLElement $xmlObject
	 * @return string
	 * @static private
	 */
	private function _dumpInterface(\SimpleXMLElement $xmlObject) {
		
		$result = PHP_EOL."\$interface = array(";
		
		foreach($xmlObject->element as $element) {
			
			$action = (string) $element->target['action'];
			$method = (string) $element->target['method'];
			$params = (string) $element->target['params'];
			
			if(empty($params))
				$params = "null";
			else
				$params = "'".$params."'";
			
			$result.= PHP_EOL."\tarray('id' => '".$element['id']."', 'target' => array('action'=>'".$action."','method'=>'".$method."','params'=>".$params.")),";
		}
		
		$result = self::removeLastChar($result, array(','));
				
		$result.= PHP_EOL.");";
		
		return $result;
	}
}