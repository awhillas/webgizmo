<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * @global	boolean	Show video controls on the <video> tag
 **/
if(!defined('GZ_AUDIO_CONTROLS')) 	define('GZ_AUDIO_CONTROLS', true);

/**
 * @global	boolean	If true, then the video will start playing as soon as it is ready
 **/
if(!defined('GZ_AUDIO_AUTOPLAY')) 	define('GZ_AUDIO_AUTOPLAY', false);

/**
 * @global	boolean	If true, the video will start over again, every time it is finished.
 **/
if(!defined('GZ_AUDIO_LOOP')) 		define('GZ_AUDIO_LOOP', false);

/**
 * @global	If true, the video will be loaded at page load, and ready to run. Ignored if "autoplay" is true.
 **/
if(!defined('GZ_AUDIO_PRELOAD')) 	define('GZ_AUDIO_PRELOAD', false);

/**
 * @global	String	Text to display when the browser doesn't support the HTML5 <video> tag.
 **/
if(!defined('GZ_AUDIO_NOT_SUPPORTED_MESSAGE')) 	
	define('GZ_AUDIO_NOT_SUPPORTED_MESSAGE', 'Your browser does not support the audio tag.');

/**
 * Web Image file
 * 
 * Will create an IMG tag in the HTML.
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 **/
class AudioFileContent extends FSFile
{
	function html($format = 'html')
	{
		$attrs = array();
		if(GZ_AUDIO_CONTROLS)	$attrs['controls'] = 'controls';
		if(GZ_AUDIO_AUTOPLAY)	$attrs['autoplay'] = 'autoplay';
		if(GZ_AUDIO_LOOP) 		$attrs['loop'] = 'loop';
		if(GZ_AUDIO_PRELOAD)	$attrs['preload'] = 'preload';
		if($type = $this->getMIME(true)) $attrs['type'] = $type;
		
		switch($format)
		{
			case 'html5':
			default:
				return audio($this->getFileURL(), 'AudioFileContent', $this->getID(), $attrs);			
		}
	}
}
