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
		if(is_a($path, 'SplFileInfo'))
		{
			$path = $path->getPathname();
		}
		$this->_path = new Path($path, true);
				
		parent::__construct($this->_path->get());
		
		$this->_name_meta = $this->parse();
	}

	/**
	 * Parse the file name for _tags_
	 * 
	 * The file name format that is expected for files is:
	 * <code>
	 * _tag1_tag2_02_nice_name.extension
	 * </code>
	 * and for directories is similar with the addition of a query part
	 * <code>
	 * _tag1_tag2_02_nice_name.filter.sorter.extension
	 * </code>
	 * Any of these parts for ether can be ommited except the nice_name which 
	 * is always expected.
	 *
	 * @return Array	List of tags found in the file name.
	 **/	
	private function parse()
	{
		preg_match_all('/^(?:_+(?P<tag>[a-z]+)*_)?(?:(?P<sort>\d{2})_)?/', $this->getBasename(), $bits);

		return array(
			'tag' 	=> $bits['tag'][0],
			'sort' 	=> $bits['sort'][0]
		);
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
		elseif(is_a($path, 'Path'))
		{
			$Path = $path;
		}
		elseif(is_a($path, 'FSObject'))
		{
			return $path;
		}

		// Handler class to use if we don't find one for the extension
		$fallback_handler = ($Path->isDir())? 'FSDir': 'FSFile';
		
		// Get the extention to use in the handelr lookup map...
		if(!$extension = strtolower(FSObject::getextension(basename($Path->get()))))
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
	
	public static function getextension($filename)
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
	 * @todo Get the name without the tag or extension(s).
	 **/
	public function getCleanName()
	{	
		return FS::clean($this->getBasename('.'.$this->getextension($this->getBasename())));
	}
	
	function getTag()
	{
		return $this->_name_meta['tag'];
	}
	
	function getSort()
	{
		return $this->_name_meta['sort'];
	}
	
	// Unique ID useful for CSS
	function getID()
	{
		$id = trim(preg_replace('/[^a-zA-Z0-9]/', ' ', $this->getBasename()));
		
		return strtoupper(str_replace(' ', '_', $id));
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
		// return FS::getURL( $this );
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