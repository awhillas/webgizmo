<?php
class PHPFileContent extends TextFileContent
{	
	protected $_display_filename = 'full';
	
	function html($format = 'xhtml1.1')
	{
		return $this->renderHTML($format, highlight_string($this->_content, true));
	}
}