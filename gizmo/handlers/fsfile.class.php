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
	
} // END class 