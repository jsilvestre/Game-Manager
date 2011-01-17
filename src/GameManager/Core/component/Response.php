<?php
/**
 * Represents a Response which will be displayed
 * 
 * A Response object is a HTTP response rendered and ready to be displayed state of the application.
 *
 * @package     GameManager
 * @subpackage  Response
 * @author      Joseph Silvestre <contact@jsilvestre.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://game-manager.jsilvestre.fr
 * @since       1.0
 * @todo Define a subpackage for this class.
 */

namespace GameManager\Core\Component;

class Response {
	
	/**
	 * Collection of HTTP headers
	 * @var array
	 * @access private
	 */
	private $_headers;
	
	/**
	 * Collection of contents. It can be html, xml, text, etc.
	 * @var array
	 * @access private
	 */
	private $_contents;
	
	/**
	 * Enter description here ...
	 * @var int
	 * @see Constants of Response Class
	 */
	private $_statusCode;
	
	/**
	 * If the Response is normal : no error.
	 * @staticvar int
	 */
	const STATUS_NORMAL	= 1;
	
	/**
	 * If the Response is an error response
	 * @staticvar int
	 */
	const STATUS_ERROR	= 0;
	
	/**
	 * Constructor
	 */
	function __construct() {
		$this->_init();
	}
	
	/**
	 * Initialize the object with default values
	 * @access private
	 */
	private function _init() {
		$this->_headers = array();
		$this->_contents = array();
		$this->_statusCode = 1;
	}
	
	/**
	 * Add a HTTP header in the Headers collection IF THE HEADER IS NOT YET IN THE COLLECTION
	 * @param string $header
	 */
	function addHeader($header) {
		if(!in_array($header,$this->_headers))
			$this->_headers[] = $header;		
	}
	
	/**
	 * Add an array of HTTP headers to the Headers collection IF THE HEADER IS NOT YET IN THE COLLECTION
	 * @param array $headers
	 * @see addHeader
	 */
	function addAllHeaders(array $headers) {
		foreach($headers as $header) {
			$this->addHeader($header);
		}
	}
	
	/**
	 * Add a content to the contents collection
	 * @param mixed $content
	 */
	function addContent($content) {
		$this->_contents[] = $content;
	}
	
	/**
	 * Add an array of contents to the contents collection
	 * @param array $contents
	 * @uses addContent
	 */
	function addAllContents(array $contents) {
		foreach($contents as $content) {
			$this->addContent($content);
		}
	}
	
	/**
	 * Gives the headers collection size
	 * @return int
	 */
	function headerSize() {
		return count($this->_headers);
	}
	
	/**
	 * Gives the contents collection size
	 * @return int
	 */	
	function contentSize() {
		return count($this->_contents);
	}
	
	/**
	 * Get the headers collection
	 * @return array
	 */
	function getAllHeaders() {
		return $this->_headers;
	}
	
	/**
	 * Get the contents collection
	 * @return array
	 */
	function getAllContents() {
		return $this->_contents;
	}
	
	/**
	 * Indicates wether the contents collection is empty or not
	 * @return bool
	 */
	function isContentEmpty() {
		return $this->contentSize() == 0;
	}
	
	/**
	 * Set the response status
	 * @param int $status
	 * @uses STATUS_NORMAL
	 * @uses STATUS_ERROR
	 */
	function setStatus($status) {
		$this->_statusCode = $status;
	}
	
	/**
	 * Get the response status
	 * @return int $status
	 */
	function getStatus() {
		return $this->_statusCode;
	}
	
}