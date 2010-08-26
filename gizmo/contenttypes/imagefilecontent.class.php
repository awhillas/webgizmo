<?php
/**
 * Web Image file
 * 
 * Will create an IMG tag in the HTML.
 *
 * @package Web Gizmo
 * @author Alexander R B Whillas
 **/
class ImageFileContent extends FileContent
{
	function html($format = 'xhtml1.1')
	{
		return '<img src="'. $this->getFileURL() .'" alt="'. $this .'" class="ImageFileContent" />';
	}
}
