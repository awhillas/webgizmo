<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Handler for PHP files
 * 
 * Uses PHP's native PHP syntax highlighting
 * 
 * @package WebGizmo
 * @subpackage	ContentHanders
 **/
class PHPFileContent extends TextFileContent
{	
	protected $_display_filename = 'full';
	
	function html($format = 'xhtml1.1')
	{
		return $this->renderHTML($format, highlight_string($this->_content, true));
	}
}