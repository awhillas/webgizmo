<?php

/**
 * File System File class
 * 
 * Represents a File System File object. 
 *
 * @package Web Gizmo
 * @author Alexander R B Whillas
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
		return file_get_contents($this->get()->getRealPath(), FILE_TEXT);
	}
	
} // END class 