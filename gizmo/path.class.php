<?php
/**
* A Path
* 
* Encapsulate common path calculations.
*
* @author Alexander R B Whillas
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
	
	public function __toString()
	{
		return $this->_path;
	}
	
	/**
	 * Invokes Path::query() when a Path object is used as a function
	 * PHP5.3 only :(
	 * @see Path::query()
	 */
	public function __invoke($filter = null, $order = null, $iterator_type = 'dir')
	{
		return $this->query($filter, $order, $iterator_type);
	}
	
	/**
	 * Factory function for the Path object 
	 */
	public static function open($path, $checkPath = false)
	{
		return new Path($path, $checkPath);
	}
	
	/**
	 * getter function
	 * @return String	The path string
	 **/
	function get()
	{
		return $this->_path;
	}
	
	/**
	 * @return 	SplFileInfo Object if its a file, NULL otherwise.
	 */
	function getFile()
	{
		if($this->isFile())
			return new SplFileInfo($this->get());
		else
			return NULL;
	}
	
	/**
	 * @return Array	The parts of the path string in an Array.
	 */
	function parts()
	{
		return explode('/', $this->get());
	}
	
	function set($path)
	{
		$this->_path = Path::clean($path);
	}
	
	/**
	 * Subtract this path _from_ the given path
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
	 * @return Path object
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
	 * List files on the given Path.
	 * 
	 * @param	$filter		Array	List of filters to apply to the listing where the 
	 * 		keys can be the name of any method of a SplFileInfo object and the values
	 * 		do the filtering. i.e. array('isFile' => true, 'getSize' => '200') would 
	 * 		get only files of size 200 bytes. TODO: Make this more complex
	 * @param	$order		Array	List of file properties to order the output by.
	 * @param	$iterator_type	String	Ether 'dir' for just this directory or 'recursive'
	 * 		for all sub directories of this Path. Defaults to 'dir'.
	 * @return 	Object	GizQuery object for further filtering
	 * @todo Sort output before we return it here. Implement QuickSort? 
	 **/
	function query($filter = null, $order = null, $iterator_type = 'dir')
	{
		$out = array();
		
		foreach ($this->getIt($filter, $iterator_type) as $File)
		{
			// Since cloning $File doesn't work
			$out[$File->getRealPath()] = new SplFileInfo($File->getRealPath());
		}
		return new GizQuery($out);
	}
	
	/**
	 * @return 	Array	List of folders in the path.
	 */
	function folders($regex = '')
	{
		return $this->query(array('isDir' => true))->name($regex);
	}
	
	/**
	 * @return 	Array	List of files in the path.
	 */
	function files($regex = '')
	{
		return $this->query(array('isFile' => true))->name($regex);
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
	 **/
	function getIt($filters = null, $iterator_type = 'dir')
	{
		switch($iterator_type)
		{
			case 'recursive':
				return new FilteredRecursiveDirectoryIterator($this->get(), $filters);
			
			case 'dir':
			default:
				return new FilteredDirectoryIterator($this->get(), $filters);
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
	
	/**
	 * Quicksort for multi-dimentional array
	 * @param	$seq		Array	Array to sort
	 * @param	$sort_by	String	Array key used to sort on
	 * @param	$order		String	Ether asc = ascending, des = descending
	 * @see http://en.wikibooks.org/wiki/Algorithm_Implementation/Sorting/Quicksort#PHP
	 * @todo test this works and then use it in list()
	 */
	// static function quicksort($seq, $sort_by, $order = 'asc') 
	// {
	// 	if(!count($seq)) return $seq;
	// 
	// 	$k = $seq[0];
	// 	$x = $y = array();
	// 
	// 	$length = count($seq);
	// 
	// 	for($i = 1; $i < $length; $i++) 
	// 	{ 
	// 		if(Path::$order($seq[$i]->$sort_by(), $k->$sort_by())) 
	// 		{
	// 			$x[] = $seq[$i];
	// 		} 
	// 		else 
	// 		{
	// 			$y[] = $seq[$i];
	// 		}
	// 	}
	// 	return array_merge(Path::quicksort($x, $sort_by, $order), array($k), Path::quicksort($y, $sort_by, $order));
	// }
	// // Comparison used in quicksort
	// static function asc($a, $b) { return $a <= $b; }
	// // Comparison used in quicksort
	// static function des($a, $b) { return $a >= $b; }
	
}

