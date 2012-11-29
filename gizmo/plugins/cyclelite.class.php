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
	
	function html($format = 'html')
	{
		$fs = FS::get();
		
		$fs->addRef(JQUERY_URL);
		//$fs->addRef('http://malsup.github.com/jquery.cycle.all.js');	// Full
		$fs->addRef(PLUGINS_URL.'/cyclelite/jquery.cycle.all.js');
				
		$out = '';
		$width = $height = 0;
		foreach($this->getContents() as $File)
		{
			$out .= $File->html()."\n";
			if($File instanceof ImageFileContent)
			{
				$width = ($File->width < $width)? $width: $File->width;
				$height = ($File->height < $height)? $height: $File->height;
			}
		}
		
		$fs->add('
			<script type="text/javascript">
				$(document).ready(function() { $("#'.$this->getID().'").cycle({ pause: true, timeout: 8000 }) });
			</script>
		');		
				
		return div($out, 'CycleLite', $this->getID());
	}
}
