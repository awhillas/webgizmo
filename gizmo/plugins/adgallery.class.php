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
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class ADGallery extends FSDir
{
	
	function html()
	{
		$fs = FS::get();
		
		//$fs->addRef('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
		$fs->addRef(JQUERY_URL);
		$fs->addRef('http://coffeescripter.com/code/ad-gallery/jquery.ad-gallery.js');
		$fs->addRef('http://coffeescripter.com/code/ad-gallery/jquery.ad-gallery.css');
		
		$fs->add('
			<script type="text/javascript">
			$(document).ready(function() {
				$(\'.ad-gallery\').adGallery();
			});
			</script>
		');
		
		$out = '';

		$out .= div('', 'ad-image-wrapper');
		$out .= div('', 'ad-controls');
		
		$out .= '
			<div class="ad-nav">
				<div class="ad-thumbs">
		';
		
		$imgs = array();

		// Assume the first folder in this folder is full of thumbnails.
		// Thumbnails are assumed to have the exact same name as the 
		// larger version in this folder.
		if($thumbs = $this->getPath()->folders()->first())
		{
			foreach($thumbs->images() as $Image) 
				$imgs[] = a($this->getFileURL().'/'.$Image->getFilename(), $Image->html());
		} 
		else
		{
			p('No thumbnail folder detected in:'.$this->getPath());
		}

		$out .= ul($imgs, 'ad-thumb-list');
		
		$out .= '
				</div>
			</div>
		';		
		return div($out, 'ad-gallery', $this->getID());
	}
}
