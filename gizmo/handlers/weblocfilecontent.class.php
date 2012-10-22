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
class WeblocFileContent extends FSFile
{
	function html($format = 'html')
	{
		$attrs = array('title' => $this->getCleanName());
		try {
			$xml = @ new SimpleXMLElement($this->getContents());
		} catch (Exception $e) {
			trigger_error('ERROR: Could not read binary webloc file: '.$this->getFilename());
			return comment('ERROR: Could not read binary webloc file: '.$this->getFilename());
		}
		return a($xml->dict->string, $this->getCleanName(), 'Webloc Url', $this->getID(), $attrs);
	}
}