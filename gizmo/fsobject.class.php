<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/


/**
 * File System Object
 * 
 * A general wrapper for a File System Object.
 *
 * @package WebGizmo
 **/
class FSObject extends SplFileInfo
{
	/**
	 * Path to the file 
	 *
	 * @var Path
	 **/
	protected $_path;
	
	public function __construct($path)
	{
		if(is_a($path, 'SplFileInfo'))
		{
			$path = $path->getPathname();
		}
		$this->_path = new Path($path, true);
		
		parent::__construct($this->_path->get());
	}

	/**
	 * Factory function to determine the Handler for the given system path.
	 * Can be a path to a file or a directory.
	 *
	 * @param	$path	String | SplFileInfo	The absolute (Real) path to the file.
	 * @return 	void
	 **/
	public static function make($File)
	{
		if(!is_a($File, 'FSObject'))
		{
			if(is_a($File, 'Path'))
			{
				$File = new FSObject($File->get());
			}
			elseif(is_string($File))
			{
				if(file_exists($File))
					$File = new FSObject($File);
				else
				{
					trigger_error('File does not exist?  '.pr($File, TRUE));

					return null;
				}
			}
			else
			{
				trigger_error('Expecting file path (String) or File (FSObject)');
				sd();
				return null;
			}
		}

		$extention = FSObject::getExtention($File->getbaseName());
		
		// Handler class to use if we don't find one.
		$fallback_handler = 'FSFile';
		
		if(!$extention)
		{
			// NO extention so look up generic handler class for the general type.
			if($File->isDir())
			{
				$extention = 'dir';
				$fallback_handler = 'DirContent';
			}
			else
			{
				$extention = 'file';
			}
		}

		// Lookup content map for file hander			
		if($handler_class = HandlerMap::get()->lookup($extention))
		{
			return new $handler_class($File);
		}
		else
		{
			// trigger_error("Unknown extension type $extention!");

			return new $fallback_handler($File);
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
	 * @return Mixed
	 **/
	public function getContents()
	{
		return null;
	}
	
	public static function getExtention($filename)
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
		return FS::clean($this->getBasename('.'.$this->getExtention($this->getBasename())));
	}
	
	/**
	 * Parse the file name for _tags_
	 *
	 * @return Array	List of tags found in the file name.
	 * @todo Implement.
	 **/
	function getTags($filename = '')
	{
		return array();
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
	 * @param	$how	The format to render. Supports 'html' (default), 'rss' and 'text' (plain text)
	 * @param	$format	
	 *
	 * @return String	In the appropriate format
	 * @todo Finish other rending formats i.e. RSS, plain text, RTF, PDF ... 
	 **/
	public function render($how = 'html', $format = 'xhtml1.1')
	{
		switch($how)
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
	 * @param	$format	The HTML standard to render to. This does nothing at the 
	 * 		moment but makes it future proof for HTML5 etc.
	 * @return String	HTML representation of the file.
	 */	
	function html($format = 'xhtml1.1') 
	{
		return '<a href="'. $this->_path->url() .'" class="'.get_class($this).'">'. $this->getCleanName() .'</a>';
	}

//	abstract public function xml($format = 'xml1.0');

	/**
	 * Default text rendering
	 */
	public function text($format = 'utf8')
	{
		switch($format)
		{
			case 'utf8':
			default:
				// Force invoke __toString()
				return utf8_encode($this);
		}
	}
	
}