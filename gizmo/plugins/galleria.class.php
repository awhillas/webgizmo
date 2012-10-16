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
 * @link http://galleria.io/
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class Galleria extends GizDirPlugin
{
	
	function html($format = 'html')
	{
		$id = $this->getID();
		$fs = FS::get();
		
		// CSS
		$fs->addRef(PLUGINS_URL.'/galleria/themes/classic/galleria.classic.css');
		
		// Javascript
		$fs->addRef(JQUERY_URL);	// Include the CLI version of JQuery.
		$fs->addRef(PLUGINS_URL.'/galleria/galleria-1.2.8.min.js');
		$fs->addRef(PLUGINS_URL.'/galleria/themes/classic/galleria.classic.min.js');
		$fs->add('<script type="text/javascript">$(document).ready(function() { Galleria.run("#'.$id.'") }) </script>');
		
		$out = '';
		// Assume the first folder in this folder is full of thumbnails (if it exists).
		$hasThumbnails = false;
		if($thumbs = $this->getPath()->folders()->first())
		{
			$thumbs = $thumbs->images();
			$hasThumbnails = $thumbs->tally() > 0;
		}
		$bigImgs = $this->images();
		$maxWidth = $maxHeight = 0;
		foreach($bigImgs as $large)
		{
			$maxWidth = ($maxWidth < $large->width)? $large->width: $maxWidth;
			$maxHeight = ($maxHeight < $large->height)? $large->height: $maxHeight;
			if($hasThumbnails)
			{
				foreach($thumbs as $small)
				{
					if($small->getBasename() == $large->getBasename())
					{
						$out .= a($large->getFileURL(), $small->html());
						break;
					}
				}
			}
			else
			{
				$out .= $large->html()."\n";				
			}
			
		}
		$fs->add('<style>#'.$id.'{ width: '.$maxWidth.'px; height: '.$maxHeight.'px; }</style>');

		return div($out, 'Galleria', $id);
	}
}
