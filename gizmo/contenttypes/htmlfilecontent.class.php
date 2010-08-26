<?php
/**
 * Includes the HTML content dirctly
 *
 * @package Web Gizmo
 * @author Alexander R B Whillas
 * @todo Look for <body> tag and only include the markup between + apply Tidy if installed? 
 **/
class HTMLFileContent extends TextFileContent
{	
	function html($format = 'xhtml1.1')
	{
		return $this->_content;
	}
	
	function text()
	{
		return strip_tags($this->_content);
	}
	
}
