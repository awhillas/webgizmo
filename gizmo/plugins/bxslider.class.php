<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * The Responsive jQuery Content Slider.
 * 
 * It scales content to fit the width of its container. This will mean
 * If the content is too small it will stretch to fit so you need to make
 * sure the content is the exact size of its container box.
 * 
 * @link http://www.bxslider.com/
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class bxSlider extends GizDirPlugin
{
	function html($format = 'html')
	{
		$fs = FS::get();
		
		$fs->addRef(JQUERY_URL);	// Include the CLI version of JQuery.
		$fs->addRef(PLUGINS_URL.'/bxslider/jquery.bxslider.min.js');
		$fs->addRef(PLUGINS_URL.'/bxslider/jquery.bxslider.css');
		
		$fs->add('<script type="text/javascript">$(document).ready(function() { $(".bxslider").bxSlider({adaptiveHeight: true}); });</script>');
		
		$images = array();
		foreach($this->getContents() as $File)
			if($File instanceof ImageFileContent)
				$images[] = $File->html();
		
		return ul($images, 'bxslider');
	}
}
