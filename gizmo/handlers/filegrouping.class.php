<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Group of files
 * 
 * Renders the contents of a folder. Uses the clean name as a title to the group.
 * 
 * @package WebGizmo
 * @subpackage	ContentHanders
 **/
class FileGrouping extends FSDir
{	
	protected $_display_filename = 'clean';
	
	public $heading_level = 1;
	
	function html($format = 'html')
	{
		$out = '';
		
		foreach($this->getContents() as $FSFile)
		{
			if($FSFile instanceof FileGrouping)
				$FSFile->heading_level = $this->heading_level + 1;
			
			$out .= $FSFile->html();
		}

		switch($format)
		{
			case 'html5':

				$out = h($this->getName(), $this->heading_level);

				$out = section($out, 'Group', $this->getID());
				
				break;
			
			case 'xhtml':
			case 'html4':	
			default:
				$out = h($this->getName(), $heading_level + 1) . $out;

				$out = div($out, 'Group', $this->getID());
		}
		
		return $out;
	}
}