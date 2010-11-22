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
abstract class GizFormat
{
	/**
	 * Format token.
	 * 
	 * @var	String
	 */
	protected $format;	
	
	/**
	 * Version of the format.
	 * 
	 * @var	String
	 */
	protected $version;
	
	protected function __construct($version)
	{		
		$this->version = $version;
	}
	
	/**
	 * Factory function for mapping formats to Layout(ors)/render'ers
	 *
	 * @return void
	 * @todo Add JSON, RSS, XML Layoutors etc.
	 **/
	public static function make($format = 'html', $version = null)
	{
		switch($format)
		{
			case 'xhtml':
			case 'html':
			default:
				if(defined('HTML_LAYOUT') and class_exists(HTML_LAYOUT))
				{
					$Layoutor = HTML_LAYOUT;
					
					return new $Layoutor($format, $version);
				}
				else
					return new FormatHTML($format, $version);
		}
	}
	
	/**
	 * Render the content in the format.
	 */
	abstract function render();
		
} // END class GizLayoutor
