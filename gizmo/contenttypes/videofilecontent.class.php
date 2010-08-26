<?php
// Show video controls on the <video> tag
if(!defined('GZ_VIDEO_CONTROLS')) 	define('GZ_VIDEO_CONTROLS', true);
// If true, then the video will start playing as soon as it is ready
if(!defined('GZ_VIDEO_AUTOPLAY')) 	define('GZ_VIDEO_AUTOPLAY', false);
// If true, the video will start over again, every time it is finished.
if(!defined('GZ_VIDEO_LOOP')) 		define('GZ_VIDEO_LOOP', false);
// If true, the video will be loaded at page load, and ready to run. Ignored if "autoplay" is true.
if(!defined('GZ_VIDEO_PRELOAD')) 	define('GZ_VIDEO_PRELOAD', false);

// Text to display when the browser doesn't support the HTML5 <video> tag.
if(!defined('GZ_VIDEO_NOT_SUPPORTED_MESSAGE')) 	
	define('GZ_VIDEO_NOT_SUPPORTED_MESSAGE', 'Your browser does not support the video tag.');

/**
 * Web Image file
 * 
 * Will create an IMG tag in the HTML.
 *
 * @package Web Gizmo
 * @author Alexander R B Whillas
 * @todo Needs some work to support older html formats
 * @todo Sniff the CODEC + MIME from the filename
 * @todo Perhaps turn 3 videos with the same name into 1 set of nested VIDEO tags?
 **/
class VideoFileContent extends FileContent
{
	function html($format = 'xhtml1.1')
	{
		$attrs = array();
		if(GZ_VIDEO_CONTROLS)	$attrs['controls'] = 'controls';
		if(GZ_VIDEO_AUTOPLAY)	$attrs['autoplay'] = 'autoplay';
		if(GZ_VIDEO_LOOP) 		$attrs['loop'] = 'loop';
		if(GZ_VIDEO_PRELOAD)	$attrs['preload'] = 'preload';
		
		switch($format)
		{
			case 'html5':
			default:
				return video($this->getFileURL(), 'VideoFileContent', $this->file->getFilename(), $attrs);			
		}
	}
	
	function guessMIME()
	{
		
	}
}
