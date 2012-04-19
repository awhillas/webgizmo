<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

require_once 'includes/html.php';

/**
 * File System Object
 * 
 * A general wrapper for a File System Object.
 *
 * @package WebGizmo
 **/
abstract class FSObject extends SplFileInfo
{
	/**
	 * Path to the file 
	 *
	 * @var Path
	 **/
	protected $_path;
	
	// Tag from file name.
	protected $_name_meta;
	
	public function __construct($path)
	{
		// if($path instanceof SplFileInfo)
		// {
		// 	$path = $path->getPathname();
		// }
		$this->_path = new Path($path, true);
				
		parent::__construct($this->_path->get());
		
		$this->_name_meta = $this->parse($this->getBasename());
	}

	/**
	 * Parse the file name for tags, sort number, name and extension.
	 * 
	 * The file name format that is expected for files is:
	 * <code>
	 * _tag_02_name.extension
	 * </code>
	 * and for directories is similar with the addition of a query part
	 * <code>
	 * _tag_02_name.filter.sorter.extension
	 * </code>
	 * Any of these parts for ether can be omitted except the 'name' which 
	 * is always expected.
	 *
	 * @return Array	List of tags found in the file name. Keys are: 'tag', 'sort, 'name' and 'extension'.
	 **/	
	private static function parse($filename)
	{
//echo $filename;		
		$regex = '/(?:_(?P<tag>[\w]+)_)?(?:(?P<sort>\d{2})?_)?(?P<name>[^\.]+)?(\.(?P<ext>\w+))?/';	// My last variant
		//$regex = '~^(?:_(?P<tag>[A-Za-z0-9]+)_)?(?:(?P<sort>\d{2})?_)?(?P<name>\w+)(\.(?P<ext>\w+))?$~';	// from stackoverflow.com
		preg_match_all($regex, $filename, $bits, PREG_SET_ORDER);
		
		if(isset($bits[0]))
		{
			$bits = $bits[0];
//pr($bits);
			return array(
				'tag' 		=> (isset($bits['tag']))? $bits['tag']: '',
				'sort' 		=> (isset($bits['sort']))? $bits['sort']: '',
				'name'		=> (isset($bits['name']))? $bits['name']: '',
				'extension'	=> (isset($bits['ext']))? $bits['ext']: '',
			);
		}
	}

	/**
	 * Getter for the 'name' part of the file.
	 * 
	 * @see FSObejct::parse()
	 * 
	 * @return string	Just the "nice name" of the file less, tags, sort number and extension.
	 **/
	public function getName()
	{
		return $this->_name_meta['name'];
	}
	
	function getTag()
	{
		return $this->_name_meta['tag'];
	}
	
	function getSort()
	{
		return $this->_name_meta['sort'];
	}
	
	/**
	 * Get Extension from filename parsing.
	 */
	function getExt()
	{
		return $this->_name_meta['extension'];
	}

	/**
	 * Factory function to determine the Handler for the given system path.
	 * Can be a path to a file or a directory.
	 *
	 * @param	$path	String | Path | FSObject	The absolute (Real) path to the file.
	 * @return 	void
	 **/
	public static function make($path)
	{
		// Process arguments
		
		if(is_string($path))
		{
			$Path = new Path($path, true);
		}
		elseif($path instanceof Path)
		{
			$Path = $path;
		}
		elseif($path instanceof FSObject)
		{
			return $path;
		}

		// Handler class to use if we don't find one for the extension
		$fallback_handler = ($Path->isDir())? 'FSDir': 'FSFile';
		
		// Get the extention to use in the handelr lookup map...
		if(!$extension = strtolower(FSObject::getEnd(basename($Path->get()))))
			$extension = ($Path->isDir())? 'dir': 'file';

		// Lookup content map for file hander			
		if($handler_class = HandlerMap::get()->lookup($extension))
		{			
			return new $handler_class($Path);
		}
		else
		{
			// trigger_error("Unknown extension type $extension!");

			return new $fallback_handler($Path);
		}
	}

	/**
	 * @return 	Path	The Path object represented by this FSObject.
	 **/
	public function getPath()
	{
		return $this->_path;
	}

	/**
	 * Get the content of the file or directory
	 * 
	 * @return NULL
	 **/
	abstract public function getContents();
	
	/**
	 * Get the extension, but getExtention is taken by SplFileInfo and getExt is a non-static here :-/
	 */
	public static function getEnd($filename)
	{
		$parts = explode('.', $filename);

		if(count($parts) > 1)
		{
			return end($parts);
		}
		else
			return false;
	}
	
	/**
	 * @return String	Filename without beginning number proceeded by an underscore nor the extension.
	 **/
	public function getCleanName()
	{
		return FSObject::clean($this->getName());
	}

	/**
	 * Clean a filename for Display purposes.
	 *
	 * @return String
	 **/
	public static function cleanName($filename)
	{
		$parts = FSObject::parse($filename);
		
		return FSObject::clean($parts['name']);
	}
	
	/**
	 * Strip leading numbers + underscore from the given string
	 * + escape HTML special characters
	 * 
	 * @return 	String
	 * 
	 * @todo make this work (with reg. expr.'s?)
	 */
	public static function clean($value = '')
	{
		// Replace underscores with spaces
		$value = preg_replace('/_/', ' ', $value);
		
		return htmlentities($value, ENT_NOQUOTES, CHAR_ENCODING);
	}
	
	// Unique ID useful for CSS
	function getID()
	{
		$id = trim(preg_replace('/[^a-zA-Z0-9]/', ' ', $this->getName()));
		
		return strtoupper(str_replace(' ', '_', $id.'_'.$this->getExt()));
	}

	/**
	 * Returns a Files virtual URL, that is relative to the virtual ?path=
	 * @return String	
	 **/
	function getURL()
	{		
		//return FS::getURL(FS::getPath()->add($this->getFilename()));
		return $this->_path->url();
	}

	/**
	 * Direct link to the file in the _content_ Dir.
	 */
	function getFileURL()
	{		
		return $this->_path->realURL();
	}

	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //
	// Rendering methods
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //
	
	/**
	 * Polymorphic function for rendering the file content to a particular format.
	 * 
	 * @param	$format	The format to render. Supports 'html' (default), 'rss' and 'text' (plain text)
	 *
	 * @return String	In the appropriate format
	 * @todo Finish other rending formats i.e. RSS, plain text, RTF, PDF ... 
	 **/
	public function render($format = 'html')
	{
		switch($format)
		{
			case 'text':
				return $this->text($format);
			
			case 'rss':
				return $this->rss($format);
			
			case 'html':
			default:
				return $this->html($format);
		}
	}
	
	/**
	 * HTML rendering
	 * 
	 * @param	$format	The HTML standard to render to. This does nothing at the 
	 * 		moment but makes it future proof for HTML5 etc.
	 * @return String	HTML representation of the file.
	 */	
	function html($format = 'html') 
	{
//		return '<a href="'. $this->_path->url() .'" class="'.get_class($this).'">'. $this->getCleanName() .'</a>';
		return a($this->_path->url(), $this->getCleanName(), get_class($this));
	}

	/**
	 * Default text rendering
	 */
	public function text()
	{
		return utf8_encode($this);
	}
	
}