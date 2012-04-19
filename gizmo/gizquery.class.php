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
	 * An array of FSObjects objects indexed with the full file path
	 */
	public $_file_list = array();
	
	/**
	 * @param	Array	List of FSObjects
	 * @param	Boolean	Should the list be sorted by keys. This is mainly for GizQuery::run() as it might do special sorting.
	 */
	function __construct($file_list = array(), $sort = true) 
	{
		// Standardise ordering. Key should be the full path. Should be do this 
		// somewhere else? I the factory method perhaps?
		if($sort)
			ksort($file_list);

		$this->_file_list = $file_list;
	}

	/**
	 * @return 	String
	 */
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
	 * @return 	GizQuery
	 * 
	 * @todo Clean this up to loop over array('GizSorter', 'GizFilter') and 
	 * 		potentially make plugable filters/sorters. Use the Visitor pattern.
	 */
	public function run($query, $divider = '.')
	{
		$out = $this->_file_list;
		
		if(is_string($query))
		{
			// If the query is empty do nothing
			if(empty($query))
				return $this;
			
			$commands = explode($divider, $query);
		}
		else
		{
			trigger_error('Query must be a String');
			
			return new GizQuery(array());
		}

		if(count($out))
			while ($cmd = array_shift($commands)) // process each query command...
			{
				if($cmd == 'contents') // 'contents' command is special
				{
					$out = array();
					
					foreach($this->_file_list as $FSObj)
						if($FSObj instanceof FSDir)
							// Using + instead of array_merge() so we keep the keys.
							$out = (array)$out + (array)$FSObj->getPath()->query(implode($divider, $commands))->get();
				}
				else
				{	
					// Assume the next part is a paramter if its not a known filter or sorter
					$param = null;
					if(
						isset($commands[0]) 
						AND ( 
							!in_array($commands[0], GizFilter::getList()) 
							AND !in_array($commands[0], GizSorter::getList()) 
						)
					) {
						$param = array_shift($commands);
					}

					if(in_array($cmd, GizFilter::getList()))
					{
						$out = GizFilter::go($cmd, $out, $param);
					}

					if(in_array($cmd, GizSorter::getList()))
					{
						$out = GizSorter::go($cmd, $out, $param);
					}
				}
			}

		return new GizQuery($out, false);		
	}
	
	/**
	 * Count the number of files
	 * @return 	Integer
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
	 * @return 	FSObject
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
	 * @return 	FSObject	Object chosen at random
	 */
	function rand()
	{
		// note: array_rand() returns a key.
		return $this->get(array_rand($this->get()));
	}
	
	/**
	 * Get the first item in the Query list
	 * @return 	FSObject
	 */
	function first()
	{	
		$r = array_reverse($this->get());
		return end($r);
	}
	
	/**
	 * Get the first item in the Query list
	 * @return 	FSObject
	 */
	function last()
	{
		return end($this->get());
	}	
		
	/**
	 * Filter files/folders by name using Regular Expression
	 * @param	String	Regular Expression to filter the name of the FSObjects by
	 * @return 	GizQuery
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
	 * @param	String	Regular Expression to filter the name of the FSObjects by
	 * @return 	GizQuery
	 */
	function folders($regex = '')
	{
		return $this->run('folders')->name($regex);
	}

	/**
	 * @param	String	Regular Expression to filter the name of the FSObjects by
	 * @return 	GizQuery
	 */
	function files($regex = '')
	{
		return $this->run('files')->name($regex);
	}

	/**
	 * Return GizQuery of all the content in the sub-folders of the folders in the current list.
	 * @param	String	Regular Expression to filter the name of the FSObjects by
	 * @return 	GizQuery
	 */
	function contents($regex = '')
	{
		$out = array();

		foreach($this->run('folders') as $folder)
		{
			if($folder->getContents()->tally() > 0)
				$out[] = $folder;
		}

		return GizQuery::merge($out);
	}
	
	/**
	 * @param	$gizes	Array	List of GizQuerys to merge
	 * @return 	GizQuery
	 */
	static function merge($gizes)
	{
		$out = array();
		
		foreach($gizes as $gq)
			if($gq instanceof GizQuery)
				$out = array_merge($out, $gq->get());
		
		return new GizQuery($out);
	}
	
	/**
	 * @param	$filter_list	array('filter' => 'value')
	 * @return 	GizQuery 
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