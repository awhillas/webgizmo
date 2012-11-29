<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

// For the htmlLink funciton
require_once 'includes/html.php';

/**
 * Controls how folders are rendered by default. This can be one of 3 ways:
 * - none: Do not render anything.
 * - link: Render an inline link in the order it is found in the current folders render order.3
 * - teaser: 	Looks for a _teaser_ subfolder in the current folder and 
 * 		renders the contents of that with a link to itself.
 */
if(!defined('FOLDER_DISPLAY')) define('FOLDER_DISPLAY', 'none');

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
	 * Should this folder appear in the menu
	 *
	 * @var Boolean
	 * @see FS::getMenu()
	 **/
	protected $_show_in_menu = true;
	
	/**
	 * Gets a list of all the content in a Directory
	 * @param	$query	String	Query string to accept
	 * @return 	GizQuery	List of FSObjects in an Array.
	 **/
	public function getContents($query = '')
	{
		$path = $this->getPath();
		
		// If a specific query isn't given then the path is used.
		if(empty($query))
			$query = $path->get();
		
		return $path->query($query);
	}
	
	/**
	 * Gets the FSDir for current directories parent directory
	 *
	 * @return FSDir
	 **/
	public function parent()
	{
		return $this->getPath()->parent()->getObject();
	}
	
	/**
	 * Return a list of images
	 *
	 * @return GizQuery
	 * @todo Perhaps get the list of extensions from the handler map file groups?
	 **/
	public function images()
	{
		// (?i) = makes the pattern to the right case insensitive.
		return $this->getContents()->files('(?i)(png|gif|jpg|jpeg)$');
	}
	
	/**
	 * List of text files
	 *
	 * @return GizQuery
	 * @todo Perhaps get the list of extensions from the handler map file groups?
	 **/
	public function texts()
	{
		// (?i) makes the patter to the right case insensitive.
		return $this->getContents()->files('(?i)(txt|text|markdown|textile)$');
	}
	
	/**
	 * HTML rendering for a directory
	 * 
	 * Display can be affected by the global FOLDER_DISPLAY which can be one of:
	 * 		'none'		- Does not display folder links.
	 * 		'teaser' 	- Looks for the presence of a sub folder in each folder 
	 * 					called "_teaser" and renders the contents of that as a link.
	 * 		'link'		- Default behavior. Renders each folder as a link using its
	 * 					"clean name" as the text.
	 * 
	 * @param	$format		Has no effect at this time. Should influence 
	 * 						rendering as ether HTML or XHTML in the future
	 *
	 * @return String
	 * @todo perhaps FOLDER_DISPLAY should be passed in here as a param. instead :-/
	 **/
	public function html($format = 'html')
	{
		$name = $this->getName();
		if (in_array($name[0], array('_', '.'))) 
			$display = 'none';
		else
			$display = FOLDER_DISPLAY;
		
		switch(strTolower($display))
		{
			case 'none':
				return '';
				break;
			
			case 'link':
			default:
				// Looks for a "_teaser" subfolder in the current folder and 
				// renders the contents of that with a link to itself.
				if($TeaserDir = $this->getContents()->folders('^_teaser')->first())
				{
					$out = '';

					foreach($TeaserDir->getContents() as $FSObject)
						$out .= $FSObject->render();

					return div($this->htmlLink($out), 'Teaser');
				}
				else
					return $this->htmlLink();
		}
	}
	
	function showInMenu()
	{
		return $this->_show_in_menu;
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