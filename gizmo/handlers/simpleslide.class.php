<?php

/**
 * A jQuery slideshow plugin.
 * 
 * @link http://www.simplesli.de/
 */
class SimpleSlide extends FSDir
{
	/**
	 * @todo use clean name when its cleaned up :)
	 */
	function html($format = 'html')
	{
		static $count = 1;
		
		// Add to the header...
		FS::get()->add('<script type="text/javascript">$(document).ready( function(){ simpleSlide(); });</script>');
		
		// Auto-sildeshow 
		FS::get()->add(
		'<script type="text/javascript"> 
			$(".auto-slider").each( function() {
			    var related_group = $(this).attr("rel");
			    window.setInterval("simpleSlideAction(\'.right-button\', " + related_group + ");", 4000);
			});
		</script>'
		);
		
		// $name = $this->getCleanName(); // clean name ain't so clean yet
		$name = 'simpleslide'.$count;
		
		$out = '
		<div class="simpleSlide-window" rel="'.$name.'">
		    <div class="simpleSlide-tray" rel="'.$name.'">
		';
		
		foreach($this->getContents() as $File) 
			$out .= "\n".div($File->html(), 'simpleSlide-slide auto-slider', '', array('rel' => $name));
		
		$out .= '
		    </div>
		</div>
		';
		
		$count++;
		
		return $out;
	}
}
