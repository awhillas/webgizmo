<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * @global	String	Name of the file in the content directory with a list of filename patterns to ignore.
 */
if(!defined('GZ_IGNORE_FILENAME')) define('GZ_IGNORE_FILENAME', '_ignore');

/**
 * Iterator class which filters files based on the Ignore list file.
 * 
 * If the CONTENT_DIR has a file called '_ignore' it will be opened and 
 * each line can be a regular expression of filenames to ignore when parsing 
 * the CONTENT_DIR folder. This is so stuff like .DS_Store files can be ignored.
 *
 * @package WebGizmo
 **/
class FSFilterIterator extends FilterIterator
{
	/**
	 * Implement abstract function from FilterIterator
	 *
	 * @return boolean TRUE and the file passes, FALSE and its filtered out.
	 **/
    public function accept()
    {	
		$current = $this->current();

		foreach($this->getIgnoreList() as $pattern)
			if(preg_match('/'.$pattern.'/', $current->getFilename()))
				return false;
		
        return true;
    }

	protected function getIgnoreList()
	{
		static $list;
		
		if(!is_array($list))
		{
			$ignore_filename = FS::get()->contentRoot().'/'.GZ_IGNORE_FILENAME;
			if(file_exists($ignore_filename))
			{
				$list = file($ignore_filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			}
			else
				$list = array();
			// ^\.{1,2}[\w\s]* is anything beginning with 1 or 2 periods followed by characters or spaces.
			$list = array_merge(array('^\.{1,2}[\w\s]*', GZ_IGNORE_FILENAME), $list);
		}
		return $list;
	}
}