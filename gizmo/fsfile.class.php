<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * File System File class
 * 
 * Represents a File System File object. 
 *
 * @package WebGizmo
 **/
class FSFile extends FSObject
{
	/**
	 * Gets the contents of the file.
	 * 
	 * @return String	Text if its a text file, binary otherwise.
	 * 
	 * @todo Figure out why the inherited getBasePath() method is returning FALSE here???
	 **/
	public function getContents()
	{
		return file_get_contents($this->_path->get(), FILE_TEXT);
	}

	/**
	 * Try to figure out the MIME type of the file.
	 *
	 * @return void
	 **/
	function getMIME() 
	{
		// For PHP 5.3 or greater
		if(class_exists('finfo'))
		{
			$finfo = @new finfo(FILEINFO_MIME); 
			$type = @$finfo->file($this->getPath()); 
			if (is_string($type) && !empty($type))
			   return $type;
		}
		
		// For old PHP before 5.3 use depreciated mime_content_type()
		if(function_exists('mime_content_type'))		
			return mime_content_type($this->getPath()->get());
			
		return false;
	}	
} // END class 