<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Generic media file.
 * 
 * Uses the standards Object tag method for embedding. Try's to guess the MIME 
 * type of the file with the extension
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 **/
class GenericFileContent extends FSFile
{	
	protected $_width = '';		// override width
	protected $_height = '';	// override height
	protected $_type = '';		// content type for data
	
	function html($format = 'html')
	{
		$attrs = array(
			'type' => (!empty($this->_type))? $this->_type: $this->getMIME()
		);
		if(!empty($this->_width))	$attr['width'] 	= $this->_width;
		if(!empty($this->_height))	$attr['height']	= $this->_height;

		return object(
			$this->getFileURL(), 	// data
			array(),				// params
			a($this->getFileURL(), $this->getBasename(), get_class($this)),// Just make a link to the file if browser can't handle object.
			get_class($this), 		// CSS class
			$this->getID(), 		// CSS ID
			$attrs					// Additional attributes
		);
	}
}