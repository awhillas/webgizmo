<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Apply one of a set of filters to an array of Files (FSObject).
 * 
 * Use the static go() method to do the dirty work. 
 *
 * @see GizCommand, GizSorter
 * @package WebGizmo
 * @author Alexander Whillas
 **/
class GizFilter extends GizCommand
{
	var $filter;
	
	var $param;
	
	/**
	 * Private as its never called directly.
	 * @param	$filter	String
	 **/
	private function __construct($filter, $param = null) 
	{ 
		$this->filter = $filter;
		
		$this->param = $param;
	}
	
	/**
	 * Run the $filter on the $subject(s)
	 * 
	 * Instantiates an instance of this class and ether applies a filter to
	 *  (a) The array passed, or
	 *  (b) Each of the FSObjects (SplFileInfo objects) in the array ($subject).
	 * 
	 * @param	$filter		String	The name of the filter to apply
	 * @param	$subject	Array	List of SplFileInfo objects
	 * @param	$param		Mixed	String or Integer to pass to the filter function.
	 * 
	 * @return 	Array		The $subject array after it has been filtered.
	 */
	public static function go($filter, $subject, $param)
	{	
		// We make an instance so we can pass the param.s
		$i = new GizFilter($filter, $param);
		
		// Are we filtering on files in the array or the array itself?
		if(in_array($filter, GizFilter::getArrayFilters()))
		{
			return call_user_func(array($i, $filter), $subject, $param);
		}
		else
		{
			// Not really sure if this is much of a speed improvement...?
			return array_filter( $subject, array($i, 'dispatch') );
		}	
	}
	
	public function dispatch($File)
	{
		return call_user_func(array($this, $this->filter), $File, $this->param);
	}
	
	/**
	 * @return 	Array	List of filter function names.
	 */
	public static function getList()
	{
		return array_merge(
			GizFilter::getArrayFilters(), 
			GizFilter::getFileFilters(), 
			array('contents')
		);
	}
	
	/**
	 * @return 	Array	List of filter function names that filter Array objects
	 */
	public static function getArrayFilters()
	{
		return array('first', 'last', 'pick');
	}

	/**
	 * @return 	Array	List of filter function names that filter File (FSObject) objects.
	 */	
	public static function getFileFilters()
	{
		return array('has', 'files', 'folders', 'from', 'to', 'tag');
	}
	
	// - - - - - - - - - - - - - - - - - - - //
	// - - - - -  Array truncating - - - - - //
	// - - - - - - - - - - - - - - - - - - - //
	
	protected static function first($subject, $n = 1)
	{
		return array_slice($subject, 0, $n, TRUE);
	}
	
	protected static function last($subject, $n = 1)
	{
		return array_slice($subject, count($subject) - $n, $n, TRUE);
	}
	
	/**
	 * Randomly choose $n number from the $subject array
	 */
	protected static function pick($subject, $n = 1)
	{
		$out = array();
		
		if(!empty($subject))
		{
			if(is_null($n) OR empty($n) OR !is_numeric($n))
				$n = 1;

			// array rand returns the KEY if there is only one item requested so... 
			if ($n == 1) 
				$keys = array(array_rand($subject)); 
			else 
				$keys = array_rand($subject, $n); 

			foreach ($keys as $k)
				$out[$k] = $subject[$k];			
		}		
		return $out;		
	}
	
	// - - - - - - - - - - - - - - - - - - - - - - - //
	// - - - - -  Array content filtering - - - - -  //
	// - - - - - - - - - - - - - - - - - - - - - - - //

	/**
	 * File name contains the given string ($needle)
	 * 
	 * @param	$File	SplFileInfo
	 * @param	$needle	String	What we're looking for in the $File(s) name.
	 */
	protected function has($File, $regex)
	{
		// strpos returns FALSE if not found but also 0 if in 1st position
		//return !(strpos($File->getBasename(), $needle) === FALSE);
		
		return preg_match("/$regex/", $File->getFilename());
	}
	
	/**
	 * Looks for give _tag_ in the base name.
	 * 
	 * Tags are defined to appear at the beginning of the file name and start 
	 * and end with an underscore '_'
	 *
	 * @param	$File	FSObject
	 * @param	$needle	String	Tag name we are looking for in the name.
	 * @return Boolean
	 * @todo 	Find out why this is working but pulling errors sometimes...?
	 **/
	protected function tag($File, $needle)
	{	
		return $File->getTag() == $needle;
	}
	
	/**
	 * Files only.
	 * 
	 * @param	$File	SplFileInfo
	 */
	protected function files($File)
	{
		return $File->isFile();
	} 
	/**
	 * Folders only.
	 * 
	 * @param	$File	SplFileInfo
	 */
	protected function folders($File)
	{
		return $File->isDir();
	}
	
	/**
	 * Accept Files _from_ a certain date.
	 * 
	 * @param	$File	SplFileInfo
	 * @param	$target	Date in the format YYYY-MM-DD or YYYY-MM or YYYY
	 */
	protected function from($File, $date)
	{
		return _date_filter($File, $date, TRUE);
	}

	/**
	 * Accept files before a certain date.
	 * 
	 * @param	$File	SplFileInfo
	 * @param	$target	Date in the format YYYY-MM-DD or YYYY-MM or YYYY
	 */	
	protected function to($File, $date)
	{
		return _date_filter($File, $date, FALSE);
	}

	/**
	 * from() and to() are basically the same except for the comparison operator.
	 * 
	 * @param	$File	SplFileInfo
	 * @param	$target	Date in the format YYYY-MM-DD or YYYY-MM or YYYY
	 * @param	$above	Is the $Files date supposed to be above the give $date?
	 */		
	private function _date_filter($File, $date, $above = TRUE)
	{
		$bits = explode('-', $date);
		$granuality = array('Y', 'm', 'd');
		
		for($i = 0; $i < count($bits); $i++)
		{
			$files_date = (int) date($granuality[$i], $File->getMTime());
			$comparison = (int) $bits[$i];
			
			if($above)
			{
				if($files_date <= $comparison)
					return FALSE;
			}
			else
			{
				if($files_date > $comparison)
					return FALSE;
			}
		}
		return TRUE;
	}
}