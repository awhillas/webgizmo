<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
* A Path
* 
* Encapsulate common path calculations. 
* Note: that a Path doesn't have to be real, just fit into the 
* @package WebGizmo
*/
class Path
{
	private $_path;
		
	function __construct($path, $checkPath = false)
	{
		if(is_a($path, 'SplFileInfo'))
			$path = $path->getPathname();
		
		if(!$checkPath OR file_exists($path = realpath($path)))	// tricky ;)
		{
			$this->set($path);
		}
		else
		{
			$trace = debug_backtrace();
			trigger_error(
				'Path does not exist: "' . $path .
				'" in ' . $trace[0]['file'] .
				' on line ' . $trace[0]['line'],
				E_USER_NOTICE);
			return null;
		}
	}

	/**
	 * Factory function for the Path object 
	 */
	public static function open($path, $checkPath = false)
	{
		return new Path($path, $checkPath);
	}
	
	
	public function __toString()
	{
		return $this->_path;
	}
	
	/**
	 * Invokes Path::query() when a Path object is used as a function
	 * PHP5.3 only :(
	 * @see Path::query()
	 */
	public function __invoke($query, $recursively = FALSE, $divider = '.')
	{
		return $this->query($query, $recursively, $divider);
	}
	
	/**
	 * Get'er function for the path string
	 * 
	 * @return String	The path string
	 **/
	function get()
	{
		return $this->_path;
	}
	
	/**
	 * @return 	FSFile	Object if its a file, NULL otherwise.
	 */
	function getFile()
	{
		if($this->isFile())
			return new FSFile($this->get());
		else
			return NULL;
	}
	
	/**
	 * @return 	Array	The parts of the path string in an Array.
	 */
	function parts($split_on = '/')
	{
		return explode($split_on, $this->get());
	}
	
	function set($path)
	{
		$this->_path = Path::clean($path);
	}
	
	/**
	 * Subtract this path _from_ the given path
	 * 
	 * @param	$haystack	String	Path to subtract from.
	 * @return 	String|Boolean		The given path less this path. False if this path is not in the given path
	 **/
	function from($haystack)
	{
		if(strlen($haystack) and strpos($haystack, $this->get()) !== false)
		{
			return substr($haystack, strlen($this->get()), strlen($haystack));
		}	
		else
			return false;
	}
	
	/**
	 * @return 	String	Last directory on the path.
	 */
	function last()
	{
		return end(explode('/', $this->get()));
	}
	
	/**
	 * Does this Path exist?
	 *
	 * @return boolean
	 **/
	function is()
	{
		return file_exists($this->get()); // and $this->isDir();
	}
	
	function isDir()
	{
		return is_dir($this->get());
	}
	
	function isFile()
	{
		return is_file($this->get());
	}
	
	
	/**
	 * Append the given string to the path. 
	 * If this Path is to a file then the string is appended between the path and the filename.
	 * 
	 * @param	$path	String	String to append to the path
	 * @return 	Path	Path with the given string parsed and appended to it.
	 * @todo Accept a Path object as well as a String
	 */
	function append($path)
	{
		if($this->isFile())
		{
			$parts = $this->parts();
			$filename = array_pop($parts);
			array_push($parts, $path, $filename);	// PHP is so cool :-/
			return new Path(implode('/', $parts));
		}
		else	
			return new Path($this->get() . Path::clean($path));
	}
	
	/**
	 * Make sure the path begins with a '/' and does not end with one.
	 * 
	 * @todo should we make sure '\' are converted to '/' as Windows accepts the later as-well?
	 */
	static function clean($path)
	{
		if(strpos($path, '/') !== 0)
			$path = '/'.$path;
		
		if(substr($path, -1) == '/')
			$path  = substr($path, 0, -1);

		return $path;
	}
	
	/**
	 * Process a query string.
	 * 
	 * @param	$query	String	The query string which is a list of filters 
	 * 							and sorters each optionally followed by a 
	 * 							parameter and all joined together with the $divider
	 * 
	 * @return 	Object	GizQuery object for further filtering
	 * 
	 * @todo Sort output before we return it here. user usort()
	 */
	public function query($query, $recursively = FALSE, $divider = '.')
	{
		$gq = new GizQuery($this->retrieve($recursively));
		
		return $gq->run($query, $divider);
	}
	
	/**
	 * Get a list of File System Objects (FSObjects) for the current path.
	 * 
	 * @param	$recursively	Boolean		Get the list of FS objects recursively?  
	 */
	public function retrieve($recursively = FALSE)
	{
		$out = array();
		
		if($recursively)
		{
			$contents = new RecursiveDirectoryIterator($this->get());
		}
		else
		{
			$contents = new DirectoryIterator($this->get());
		}
		
		foreach ($contents as $File)
		{
			// FSObject's factory method
			$out[$File->getRealPath()] = FSObject::make($File);
		}
		return $out;
	}
	
	/**
	 * @return 	Array	List of folders in the path.
	 */
	function folders($regex = '')
	{
		return $this->query('folders')->name($regex);
	}
	
	/**
	 * @return 	Array	List of files in the path.
	 */
	function files($regex = '')
	{
		return $this->query('files')->name($regex);
	}
	
	/**
	 * Get directory Iterator
	 * 
	 * @param	$filter	Array	List of filters, where $key any 'is' or 'get' method of a 
	 * 		SplFileInfo object and the value is used in a comparison with the function result.
	 * @param	$iterator_type	Ether 'dir' for simple directory iterator or 'recursive' for 
	 * 		recursive directory iterator.
	 *
	 * @return FilteredDirectoryIterator
	 * 
	 * @todo Should simplify this as filtering is now done after the Iterator returns its result.
	 **/
	function getIt($filters = null, $iterator_type = 'dir')
	{
		switch($iterator_type)
		{
			case 'recursive':
				return new RecursiveDirectoryIterator($this->get());
			
			case 'dir':
			default:
				return new DirectoryIterator($this->get());
		}
	}
	
	/**
	 * @return 	Booelan	True if Path is an empty directory, false otherwise
	 * @see http://de2.php.net/manual/en/function.is-dir.php#85961
	 */
	public function isEmpty()
	{
	     return (($files = @scandir($this->get())) && count($files) <= 2);
	}
}

