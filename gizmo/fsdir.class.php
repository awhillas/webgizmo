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
	public function getContents($query = '')
	{		
		// Get the contents of the folder using the folder name as a query string.
		return $this->_path->query($this->getBasename())->run($query);
	}
	
	/**
	 * Gets the FSDir for current directories parent directory
	 *
	 * @return FSDir
	 **/
	public function parent()
	{
		return $this->_path->parent()->getObject();
	}
	
	/**
	 * Return a list of images
	 *
	 * @return GizQuery
	 * @todo Perhaps get the list of extensions from the handler map file groups?
	 **/
	public function images()
	{
		return $this->getContents()->files('(png|gif|jpg)$');
	}
	
	/**
	 * List of text files
	 *
	 * @return GizQuery
	 * @todo Perhaps get the list of extensions from the handler map file groups?
	 **/
	public function texts()
	{
		return $this->getContents()->files('(txt|text|markdown|textile)$');
	}
	
	/**
	 * Create a HTML anchor tag linking to this directory
	 *
	 * @return String	HTML anchor tag
	 **/
	public function htmlLink($content = null, $class = '', $id = '', $attributes = array())
	{
		$content = (is_null($content))? $this->getCleanName(): $content;

		return a($this->_path->url(), $content, $class, $id, $attributes);
	}
}