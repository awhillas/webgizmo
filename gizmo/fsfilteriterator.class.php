<?php

class FSFilterIterator extends FilterIterator
{
	private $_filters = array();
	
	// TODO: replace this with some Reflection?
	private $_valid_filters = array(
		'isDot', 'isDir', 'isFile', 'isReadable', 'isWritable', 'isLink',	// Booleans
		'getSize', 'getMTime', 'getCTime', 'getATime', 'getBasename', 'getFilename'	// for string comparison
	);

	public function __construct($iterator, $filters = array())
	{
		parent::__construct($iterator);
		
		$this->_filters = $filters;
		
		$this->isDot = false;		
	}
	
	/**
	 * Implement abstract function from FilterIterator
	 *
	 * @return boolean
	 **/
    public function accept()
    {	
		$current = $this->current();

		// Looking for only one negative to make it all false.
		foreach($this->_filters as $filter => $value)
			if($filter == 'name' and !$this->name($value, $current))
				return false;
			elseif(method_exists($current, $filter) and $current->$filter() != $value)
				return false;
		
        return true;
    }

	protected function name($pattern, $subject)
	{
		
// if($test = preg_match("/$pattern/", $subject))
// 	echo $pattern."\n";
// else
// 	var_dump($test);
	
		if (!empty($pattern))
			return preg_match("/$pattern/", $subject) != 0;
		else
			return true;
	}

	/**
	 * Apply the $filter to the $file
	 */
	function filter($filter, $value, $file)
	{
		if(method_exists($this, $filter))
		{
			return $this->$filter($value, $file);
		}
		else
			return $file->$filter() != $value;
	}
	
	function __set($filter, $value)
	{
		// if it doesn't begin with 'is' or 'get' then append it.
		if(strpos($filter, 'is') !== 0 and strpos($filter, 'get') !== 'get')
			$filter = 'get'.$filter;
		
		if(in_array($filter, $this->_valid_filters))
			$this->_filters[$filter] = $value;
	}
}