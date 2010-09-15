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
 **/
class ImageFileContent extends FSFile
{
	function html($format = 'xhtml1.1')
	{
		return '<img src="'. $this->getFileURL() .'" alt="'. $this .'" class="ImageFileContent" />';
	}
}
