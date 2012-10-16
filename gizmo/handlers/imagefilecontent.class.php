<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Web Image file
 * 
 * Will create an IMG tag in the HTML.
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 **/
class ImageFileContent extends FSFile
{
	public $width;
	
	public $height;

	function __construct($path) 
	{
		parent::__construct($path);

		if (extension_loaded('gd') && function_exists('getimagesize'))
		{
			$meta = getimagesize($this->getPath()->get());
			$this->width = $meta[0];
			$this->height = $meta[1]; 
		}
	}

	function html($format = 'html')
	{
		$attrs = array();
		
		if($this->width) $attrs['width'] = $this->width;
		if($this->height) $attrs['height'] = $this->height; 

		return img($this->getFileURL(), $this->getCleanName(), 'ImageFile', $this->getID(), $attrs);
	}
}