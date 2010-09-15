<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Class for handling file extensions we don't understand (have no extension to class mapping for)
 *
 * @package WebGizmo
 **/
class GenericFileContent extends FSFile
{	
	function html($format = 'xhtml1.1') 
	{
		return '<a href="'. $this->getURL() .'" class="GenericFileContent">'. $this->getCleanName() .'</a>';
	}
}