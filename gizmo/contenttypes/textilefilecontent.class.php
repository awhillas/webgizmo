<?php
include_once(INCLUDES_PATH.'/classTextile.php');
/**
 * Textile: A Humane Web Text Generator
 * 
 * Textile takes plain text with *simple* markup and produces valid XHTML. 
 * It's used in web applications, content management systems, blogging software 
 * and online forums.
 *
 * @package Web Gizmo
 * @author Alexander R B Whillas
 * @see http://textile.thresholdstate.com/
 **/
class TextileFileContent extends TextFileContent
{	
	function html($format = 'xhtml1.1')
	{
		$textile = new Textile();
		
		return $this->renderHTML($format, $textile->TextileThis($this->_content));
	}
}
