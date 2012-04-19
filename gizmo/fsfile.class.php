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
	 **/
	public function getContents()
	{
		if(is_readable($this->_path->get()))
			return file_get_contents($this->_path->get(), FILE_TEXT);
		else
			trigger_error('Can not read file: '.$this->_path->get(), E_USER_WARNING);
			return false;
	}

	/**
	 * Try to figure out the MIME type of the file.
	 * 
	 * @param	Boolean	$andEncoding	With the optional Charset encoding.
	 *
	 * @return void
	 **/
	function getMIME($andEncoding = false) 
	{
		// For PHP 5.3 or greater
		if(class_exists('finfo'))
		{
			$finfo = @new finfo(FILEINFO_MIME);
			$type = @$finfo->file($this->getPath());
			if (is_string($type) && !empty($type))
			{
				if($andEncoding)
					return $type;
				else
				{
					return array_shift(explode(';', $type));
				}
			}
		}
		
		// For old PHP before 5.3 use depreciated mime_content_type()
		if(function_exists('mime_content_type'))		
			return mime_content_type($this->getPath()->get());
			
		return false;
	}	
} // END class 