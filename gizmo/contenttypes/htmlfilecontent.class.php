<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Includes the HTML content directly.
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 * @todo Look for <body> tag and only include the markup between + apply Tidy if installed? 
 **/
class HTMLFileContent extends TextFileContent
{	
	function html($format = 'html')
	{
		return $this->_content;
	}
	
	function text()
	{
		return strip_tags($this->_content);
	}
	
}
