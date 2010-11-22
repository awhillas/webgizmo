<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * @global	boolean	Show video controls on the <video> tag.
 */
if(!defined('GZ_VIDEO_CONTROLS')) 	define('GZ_VIDEO_CONTROLS', true);

/**
 * @global	boolean	If true, then the video will start playing as soon as it is ready.
 */
if(!defined('GZ_VIDEO_AUTOPLAY')) 	define('GZ_VIDEO_AUTOPLAY', false);

/**
 * @global	boolean	If true, the video will start over again, every time it is finished.
 */
if(!defined('GZ_VIDEO_LOOP')) 		define('GZ_VIDEO_LOOP', false);

/**
 * @global	boolean	If true, the video will be loaded at page load, and ready to run. Ignored if "autoplay" is true.
 */
if(!defined('GZ_VIDEO_PRELOAD')) 	define('GZ_VIDEO_PRELOAD', false);

/**
 * @global	string	Text to display when the browser doesn't support the HTML5 <video> tag.
 */
if(!defined('GZ_VIDEO_NOT_SUPPORTED_MESSAGE')) 	
	define('GZ_VIDEO_NOT_SUPPORTED_MESSAGE', 'Your browser does not support the video tag.');

/**
 * Web Video file handler.
 * 
 * Will try to be cleaver and use HTML5 tags if the browser supports them 
 * otherwise the old <object> tag.
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 * 
 * @todo Needs some work to support older html formats
 * @todo Sniff the CODEC + MIME from the filename
 * @todo Perhaps turn 3 videos with the same name into 1 set of nested VIDEO tags?
 **/
class VideoFileContent extends FSFile
{
	function html($format = 'html')
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
				return video($this->getFileURL(), 'VideoFileContent', $this->getID(), $attrs);			
		}
	}
	
	function guessMIME()
	{
		
	}
}
