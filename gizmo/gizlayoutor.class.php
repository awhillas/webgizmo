<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Layoutor - Calls the appropriate rendering function for each piece of 
 * content and then wraps it up and returns it
 * 
 * Handles the wrapping of each piece of content in the appropriate markup
 * BUT not the overall template!
 * Also calls the appropriate render function for the format.
 * This class is mainly for being inherited and the render() function overridden. 
 *
 * @package WebGizmo
 * @abstract
 **/
abstract class GizLayoutor
{
	/**
	 * Factory function for mapping formats to Layout render'ers
	 *
	 * @return void
	 * @todo Add JSON, RSS, XML Layoutors etc.
	 **/
	public static function make($format = 'html')
	{
		switch($format)
		{
			case 'html4':
			case 'html5':
			case 'xhtml':
			case 'html':
			default:
				if(defined('HTML_LAYOUT') and class_exists(HTML_LAYOUT))
				{
					$Layoutor = HTML_LAYOUT;
					
					return new $Layoutor();
				}
				else
					return new LayoutHTML();
		}
	}
	
} // END class GizLayoutor
