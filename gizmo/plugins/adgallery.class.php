<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * A highly customizable gallery/showcase plugin for jQuery.
 * 
 * @link http://coffeescripter.com/code/ad-gallery/
 * @link http://adgallery.codeplex.com/documentation
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class ADGallery extends GizDirPlugin
{
	
	function html($format = 'html')
	{
		$id = $this->getID();
		$fs = FS::get();
		
		$fs->addRef(JQUERY_URL);	// Include the CLI version of JQuery.
		// $fs->addRef('http://coffeescripter.com/code/ad-gallery/jquery.ad-gallery.js');
		// $fs->addRef('http://coffeescripter.com/code/ad-gallery/jquery.ad-gallery.css');
		
		$fs->addRef(PLUGINS_URL.'/adgallery/jquery.ad-gallery.pack.js');
		$fs->addRef(PLUGINS_URL.'/adgallery/jquery.ad-gallery.css');
		
		$fs->add('<script type="text/javascript">$(document).ready(function() { $("#'.$id.'.ad-gallery").adGallery() });</script>');
		$fs->add('<style>.ad-gallery .ad-nav { overflow: hidden; }</style>');
		
		$out = div('', 'ad-image-wrapper');
		// Assume the first folder in this folder is full of thumbnails.
		// Thumbnails are assumed to have the exact same name as the 
		// larger version in this folder.
		if($thumbs = $this->getPath()->folders()->first())
		{
			$out .= div('', 'ad-controls');
			
			$out .= '
				<div class="ad-nav">
					<div class="ad-thumbs">
			';

			$imgs = array();
			foreach($thumbs->images() as $Image) 
				$imgs[] = a($this->getFileURL().'/'.$Image->getFilename(), $Image->html());

			$out .= ul($imgs, 'ad-thumb-list');

			$out .= '
					</div>
				</div>
			';		
		} 
		else
		{
			// Should the gallery work without thumbnails???
			comment('No thumbnail folder detected in:'.$this->getPath());
		}

		return div($out, 'ad-gallery', $id);
	}
}
