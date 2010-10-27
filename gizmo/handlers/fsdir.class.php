<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

// For the htmlLink funciton
require_once 'includes/html.php';

/**
 * Controls how folders are rendered by default. This can be one of 3 things:
 * - none: Do not render anything.
 * - link: Render an inline link in the order it is found in the current folders render order.
 */
if(!defined('FOLDER_DISPLAY')) define('FOLDER_DISPLAY', 'link');

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
	 * HTML rendering for a directory
	 *
	 * @return String
	 **/
	public function html($format = 'html')
	{
		switch(FOLDER_DISPLAY)
		{
			case 'none':
				return '';
			
			case 'link':
			default:
				return parent::html($format);
		}
	}
	
	/**
	 * Create a HTML anchor tag linking to this directory
	 *
	 * @return String	HTML anchor tag
	 **/
	public function htmlLink($content = null, $class = '', $id = '', $attributes = array())
	{
		$content = (is_null($content))? $this->getCleanName(): $content;
		
		$class = (empty($class))? get_class($this): $class;

		return a($this->_path->url(), $content, $class, $id, $attributes);
	}
}