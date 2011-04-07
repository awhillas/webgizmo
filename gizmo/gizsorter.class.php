<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Contains various File (FSObject) array sorting commands
 *
 * @see GizCommand, GizFilter, GizQuery::run()
 * @package WebGizmo
 * @todo Sort by Date
 * @todo This whole class should be rewritten to use the Visitor pattern @link http://en.wikipedia.org/wiki/Visitor_pattern
 **/
class GizSorter extends GizCommand
{
	public static function go($sort, $subject, $param)
	{
		// General array sorting, not looking at its objects.
		switch(strtolower($sort))
		{
			case 'shuffle':
			case 'random':
			case 'rand':
				return GizSorter::shuffle($subject);

			case 'reverse':
			case 'rev':
				return array_reverse($subject, TRUE);
		}		
		
		// some alias'
		switch(strtolower($sort))
		{
			case 'nicename':
				$sort = 'name';
				break;

			case 'rname':
				$sort = 'realname';
				break;

			case 'filesize':
				$sort = 'size';
				break;
		}
		
		usort($subject, array('GizSorter', $sort));
		
		return $subject;
	}
	
	/**
	 * @return 	A list of available filters in an array.
	 */
	public static function getList()
	{
		return array(
			'name', 'nicename', 'realname', 'rname', 'size', 'filesize', 'ctime', 'mtime',
			'shuffle', 'random', 'rand', 'reverse', 'rev'
		);
	}

	/**
	 * Shuffle the array keeping keys intact.
	 *
	 * @return Array
	 **/
	public static function shuffle($list) 
	{ 
		if (!is_array($list) OR count($list) < 2) 
			return $list; 

		$keys = array_keys($list); 
		
		shuffle($keys); 
		
		$random = array(); 
		
		foreach ($keys as $key) 
			$random[$key] = $list[$key]; 

		return $random;
	} 
	
	/**
	 * Compare "nice" names.
	 * Use realname (rname) for actual file name. 
	 *
	 * @return Integer
	 * @author Alexander Whillas
	 **/
	public function name($a, $b)
	{
		return strcmp($a->getCleanName(), $b->getCleanName());
	}

	/**
	 * Compare real file names
	 *
	 * @return Integer
	 **/
	public function realname($a, $b)
	{
		return strcmp($a->getFilename(), $b->getFilename());
	}
	
	/**
	 * Compare file sizes
	 *
	 * @return Integer	
	 **/
	public function size($a, $b)
	{
		return GizSorter::intcmp((int)$a->getSize(), (int)$b->getSize());
	}
	
	/**
	 * Compare creation times
	 *
	 * @return void
	 * @author Alexander Whillas
	 **/
	public function ctime($a, $b)
	{
		return GizSorter::intcmp($a->getMTime(), $b->getMTime());
	}

	/**
	 * Compare modified times
	 *
	 * @return void
	 * @author Alexander Whillas
	 **/
	public function mtime($a, $b)
	{
		return GizSorter::intcmp($a->getMTime(), $b->getMTime());
	}	
	
	/**
	 * Compare two integers.
	 * Used on a few compare functions.
	 *
	 * @return Integer
	 **/
	public function intcmp($a, $b)
	{
		if ($a == $b) 
			return 0;
		
		return ($a > $b) ? +1 : -1;
	}
	
} // END class GizSorter