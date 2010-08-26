<?php

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
	
	function tally()
	{
		return count($this->_file_list);
	}
	
	function items($index = null)	// can't call this simple 'list()'
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
		return $this->items(array_rand($this->items()));
	}
	
	/**
	 * Get the first item in the Query list
	 * @return 	SplFileInfo object
	 */
	function first()
	{
		return end(array_reverse($this->items()));
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
	 * Return GizQuery of all the sub-folders of the folders
	 */
	function contents($regex = '')
	{
		$out = array();
		
		foreach ($this->filter(array('isDir' => true)) as $item) 
		{
			if($result = Path::open($item->getPathname())->query(array('name' => $regex)) and $result->tally())
				$out[] = $result;
		}
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
				$out = array_merge($out, $gq->items());
		
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