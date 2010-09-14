<?php
/**
 * Class for handling file extensions we don't understand (have no extension to class mapping for)
 *
 * @package Web Gizmo
 * @author Alexander R B Whillas
 **/
class GenericFileContent extends FileContent
{	
	function html($format = 'xhtml1.1') 
	{
		return '<a href="'. $this->getURL() .'" class="GenericFileContent">'. $this->getCleanName() .'</a>';
	}
}