<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Windows URL file
 * 
 * Creates a HTML Anchor.
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 **/
class UrlFileContent extends FSFile
{
	function html($format = 'html')
	{
		$attrs = array('title' => $this->getCleanName());
		
		$ini = parse_ini_file($this->getPath(), true);
		
		if(isset($ini['InternetShortcut']) and $ini['InternetShortcut']['url'])
			return a($ini['InternetShortcut']['URL'], $this->getCleanName(), 'UrlFile Url', $this->getID(), $attrs);
	}
}