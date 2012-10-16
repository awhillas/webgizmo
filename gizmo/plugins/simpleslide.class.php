<?php

/**
 * A jQuery slideshow plugin.
 * 
 * @link http://www.simplesli.de/
 *
 * @package WebGizmo
 * @subpackage	GizPlugins
 */
class SimpleSlide extends GizDirPlugin
{
	/**
	 * @todo use clean name when its cleaned up :)
	 */
	function html($format = 'html')
	{
		FS::get()->addRef(JQUERY_URL);
		
		// Add to the header...
		FS::get()->add('<script>$().ready( function(){ simpleSlide(); });</script>');
		
		// Auto-sildeshow 
		FS::get()->add(
		'<script type="text/javascript"> 
			$(".auto-slider").each( function() {
			    var related_group = $(this).attr("rel");
			    window.setInterval("simpleSlideAction(\'.right-button\', " + related_group + ");", 4000);
			});
		</script>'
		);
		
		$id = $this->getID();
		
		$out = '
		<div class="simpleSlide-window" rel="'.$id.'">
		    <div class="simpleSlide-tray" rel="'.$id.'">
		';
		
		foreach($this->getContents() as $File) 
			$out .= "\n".div($File->html(), 'simpleSlide-slide auto-slider', '', array('rel' => $id));
		
		$out .= '
		    </div>
		</div>
		';
		
		return $out;
	}
}
