<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * The jQuery Cycle Lite Plugin is a slideshow plugin.
 * 
 * @link http://jquery.malsup.com/cycle/lite/
 * @package WebGizmo
 * @author Alexander R B Whillas
 */
class CycleLite extends FSDir
{
	
	function html()
	{
		$fs = FS::get();
		
		$fs->addRef('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
		$fs->addRef('http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.lite.1.0.min.js');
		
		$fs->add('
			<script type="text/javascript">
				$(document).ready(function() { $("#'.$this->getID().'").cycle() });
			</script>
		');
		
		$out = '';
		
		foreach($this->getContents() as $File) 
			$out .= $File->html()."\n";
		
		return div("\n".$out, 'CycleLite', $this->getID());
	}
}
