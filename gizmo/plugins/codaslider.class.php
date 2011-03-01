<?php

/**
 * A jQuery Tab'ed slider.
 * 
 * @link http://www.ndoherty.biz/demos/coda-slider/2.0/
 *
 * @package WebGizmo
 * @subpackage	GizPlugins
 */
class CodaSlider extends GizDirPlugin
{
	/**
	 * @todo use clean name when its cleaned up :)
	 */
	function html($format = 'html')
	{
		static $count = 1;
		
		$fs = FS::get();
		
		// Add to the header...
		
		// Javascript file references
//		$fs->addRef('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
		$fs->addRef(JQUERY_URL);
		
//		$js_folder = $fs->pluginsRoot()->add('CodaSlider')->realURL();
		$fs->addRef('http://gsgd.co.uk/sandbox/jquery/easing/jquery.easing.1.3.js');
		$fs->addRef('http://www.ndoherty.biz/demos/coda-slider/2.0/javascripts/jquery.coda-slider-2.0.js');
		// ... and some CSS.
		$fs->addRef('http://www.ndoherty.biz/demos/coda-slider/2.0/stylesheets/coda-slider-2.0.css');
		// Some code specific to this instance of the CodaSlider
		
		$name = 'CODA-SLIDER-'.$count;	// Unique name
		
		$fs->add('<script type="text/javascript">$().ready(function() { $("#'.$name.'").codaSlider(); });</script>');
		
		
		$pannels = '';
		
		foreach($this->getContents() as $folder)
		{
			$folder_contents = '';
			foreach($folder->getContents() as $File) 
				$folder_contents .= $File->html();
				
			$pannels .= div(
					div(
						h($folder->getCleanName(), 2, 'title') 
							. $folder_contents."\n",
						'panel-wrapper'
					), 
					'panel'
				);
		}
		
		$out = div(div($pannels, 'coda-slider preload', $name), 'coda-slider-wrapper');
		
		$count++;
		
		return $out;
	}
}
