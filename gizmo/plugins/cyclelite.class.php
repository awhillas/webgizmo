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
 *
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 * @todo Handle the case where there are folders with the exact same name on the page.
 */
class CycleLite extends GizDirPlugin
{
	
	function html()
	{
		$fs = FS::get();
		
//		$fs->addRef('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
		$fs->addRef(JQUERY_URL);
//		$fs->addRef('http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.lite.1.0.min.js');	// Lte
		$fs->addRef('http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.all.latest.js');	// Full
				
		$out = '';
		$width = $height = 0;
		foreach($this->getContents() as $File)
		{
			$out .= $File->html()."\n";
			if(is_a($File, 'ImageFileContent'))
			{
				$width = ($File->width < $width)? $width: $File->width;
				$height = ($File->height < $height)? $height: $File->height;
			}
		}
		
		$fs->add('
			<script type="text/javascript">
				$(document).ready(function() { $("#'.$this->getID().'").cycle() });
			</script>
		');		
				
		return div("\n".$out, 'CycleLite', $this->getID());
	}
}
