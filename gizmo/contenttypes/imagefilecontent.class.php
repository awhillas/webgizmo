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
	function html($format = 'html')
	{
		return '<img src="'. $this->getFileURL() .'" alt="'. $this->getCleanName() .'" class="ImageFileContent" />';
	}
}
