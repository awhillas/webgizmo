<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * File System Directory class
 * 
 * Represents a File System Directory object. 
 * 
 * @package WebGizmo
 **/
class FSDir extends FSObject
{
	/**
	 * Gets a list of all the content in a Directory
	 * 
	 * @return 	GizQuery	List of FSObjects in an Array.
	 **/
	public function getContents()
	{
		// Get the contents of the folder using the folder name as a query string.
		$this->_path->query($this->getBasename());
	}
}