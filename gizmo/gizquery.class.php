<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Encapsulate a list of FSObjets which are a result of a query (command chain).
 * 
 * Typically the List of FSObjects would come from a Path but as successive 
 * filters and sorters can be applied to it this list might end up bearing 
 * no relation to the original directory content pointed to by the original 
 * Path object. 
 *
 * @package WebGizmo
 **/
class GizQuery implements Iterator
{
	/**
	 * An array of SplFileInfo objects indexed with the full file path
	 */
	public $_file_list = array();
	
	function __construct($file_list) 
	{
		// Standardise ordering. Key should be the full path
		ksort($file_list);
		
		$this->_file_list = $file_list;
	}

	public function __toString()
	{
		if($this->tally())
		{
			$out = '';
			foreach($this as $file) 
				$out .= $file->getFilename()."\n";
		
			return $out;
		}
		else
			return '[empty]';
	}
	
	/**
	 * Parse a query string and return a new GizQuery object.
	 * 
	 * @return 	GizQuery	This GizQuery object
	 * 
	 * @todo Clean this up to loop over array('GizSorter', 'GizFilter') and potentially make plugable filters/sorters.
	 */
	public function run($query, $divider = '.')
	{
		$out = array();
		
		if(is_string($query))
		{
			$commands = explode($divider, $query);
		}
		else
		{
			trigger_error('Query must be a String');
			
			return new GizQuery(array());
		}
		
		$filter_list = GizFilter::getList();
		
		$sorter_list = GizSorter::getList();
		
		if(count($this->_file_list))
			while ($cmd = array_shift($commands)) 
			{
				// Assume the next part is a paramter if its not a known filter or sorter
				$param = null;
				if(
					isset($commands[0]) 
					AND ( 
						!in_array($commands[0], $filter_list) 
						OR !in_array($commands[0], $sorter_list) 
					)
				) {
					$param = array_shift($commands);
				}

				if(in_array($cmd, $filter_list))
				{
					$out = GizFilter::go($cmd, $this->_file_list, $param);
				}
			
				if(in_array($cmd, $sorter_list))
				{
					$out = GizSorter::go($cmd, $this->_file_list, $param);
				}
			}
			
		return new GizQuery($out);		
	}
	
	/**
	 * Count the number of files
	 */
	function tally()	// can't call this simply 'count()'
	{
		return count($this->_file_list);
	}

	/**
	 * Get a specific element from the FSOject list or returns the whole list 
	 * if an $index is not passed.
	 * 
	 * @param	$index	String	RealPath to a specific file in the list.
	 */
	function get($index = null)	// can't call this simply 'list()'
	{
		if(is_null($index))
			return $this->_file_list;
		else
		{	
			if(array_key_exists($index, $this->_file_list))
				return $this->_file_list[$index];
			else
				return false;
		}	
	}
	
	/**
	 * Get random item in the Query list
	 * @return 	SplFileInfo object chosen at random
	 */
	function rand()
	{
		// note: array_rand() returns a key.
		return $this->get(array_rand($this->get()));
	}
	
	/**
	 * Get the first item in the Query list
	 * @return 	SplFileInfo object
	 */
	function first()
	{
		return end(array_reverse($this->get()));
	}
		
	/**
	 * Filter files/folders by name using Regular Expression
	 */
	function name($regex = '')
	{		
		$out = array();
		
		if(!empty($regex))
		{
			foreach($this as $file)
				if(preg_match("/$regex/", $file->getFilename()))				
					$out[$file->getPathname()] = $file;
			
			return new GizQuery($out);
		}
		else
			return $this;
		
	}
	/**
	 * Filter GizQuery list for Folders
	 */
	function folders($regex = '')
	{
		return $this->filter(array('isDir' => true, 'isDot' => false))->name($regex);
	}
	
	function files($regex = '')
	{
		return $this->filter(array('isFile' => true))->name($regex);
	}

	/**
	 * Return GizQuery of all the content in the sub-folders of the folders in the current list.
	 */
	function contents($regex = '')
	{
		$out = array();

		foreach($this->run('folders') as $folder)
		{
			if($folder->getPath()->query('')->tally())
				$out[] = $folder;
		}
		
		// foreach ($this->filter(array('isDir' => true)) as $item) 
		// {
		// 	if($result = Path::open($item->getPathname())->query(array('name' => $regex)) and $result->tally())
		// 		$out[] = $result;
		// }
		return GizQuery::merge($out);
	}
	
	/**
	 * @param	$gizes	Array	List of GizQuerys to merge
	 */
	static function merge($gizes)
	{
		$out = array();
		
		foreach($gizes as $gq)
			if(is_a($gq, 'GizQuery'))
				$out = array_merge($out, $gq->get());
		
		return new GizQuery($out);
	}
	
	/**
	 * @param	$filter_list	array('filter' => 'value')
	 * @return 	GizQuery object
	 */
	function filter($filter_list)
	{
		$out = array();
		
		foreach($this as $file)
			foreach($filter_list as $filter => $value)
				if($this->test($filter, $value, $file))
					$out[$file->getPathname()] = $file;
		
		return new GizQuery($out);
	}
	
	function test($filter, $value, $file)
	{
		if(method_exists($file, $filter))
			return $file->$filter() == $value;
		elseif(method_exists($this, $filter))
			return $this->$filter($file, $value);
	}
	
	// File/folder filters - - - - - - - - - - - - - - - - - //
	
	private function isDot($file, $value)
	{
		return in_array($file->getFilename(), array('.', '..'));
	}
			
	// Iterator interface stuff - - - - - - - - - - - - - - - - - //
	
	function rewind() {
		return reset($this->_file_list);
	}
	function current() {
		return current($this->_file_list);
	}
	function key() {
		return key($this->_file_list);
	}
	function next() {
		return next($this->_file_list);
	}
	function valid() {
		return key($this->_file_list) !== null;
	}
}