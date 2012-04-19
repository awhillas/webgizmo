<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Galleria is a JavaScript image gallery framework built on top of the jQuery
 * library. The aim is to simplify the process of creating professional image
 * galleries for the web and mobile devices.
 * 
 * Supports themes and has a full screen mode.
 * 
 * @link http://galleria.aino.se/
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class Galleria extends FSDir
{
	
	function html($format = 'html')
	{
		$id = $this->getID();
		
		$fs = FS::get();
		$plugins = $fs->pluginsRoot()->realURL();
		
		$fs->addRef(JQUERY_URL);	// Include the CLI version of JQuery.
		
		$fs->addRef($plugins.'/galleria/galleria-1.2.3.min.js');
		$fs->addRef($plugins.'/galleria/themes/classic/galleria.classic.min.js');
	
		$fs->add('
			<script type="text/javascript">
				$(document).ready(function() { 
					$("#'.$id.'").galleria({
						image_crop: true,
						transition: "fade"
				    }); 
				});
			</script>
		');
		
		$out = '';

		// Assume the first folder in this folder is full of thumbnails (if it exists).
		$thumbs = $this->getPath()->folders()->first()->images();
		if($thumbs->tally() > 0) // are there any thumbnails?
		{
			$bigImgs = $this->images();
			
			foreach($thumbs as $small)
				foreach($bigImgs as $large)
					if($small->getBasename() == $large->getBasename())
					{
						$out .= a($large->realURL(), $small->html());
						break;
					}
		} 
		else
		{
			// Just dump all the images in the folder.
			foreach($this->images() as $img)
				$out .= $img->html()."\n";
		}

		return div($out, 'Galleria', $id);
	}
}
