<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * path to default extension to handler mapping. 
 * File should be in INI format.
 */
if (!defined('DEFAULT_HANDLER_MAP_PATH'))
	define('DEFAULT_HANDLER_MAP_PATH', 	GIZMO_PATH.'/default.map.ini');

/**
 * Handle the mapping between file extentions and the handler classes for them
 *
 * @package WebGizmo
 * @see FSObject
 **/
class HandlerMap
{
	private static $_map;
	
	/**
	 * Private constructor so nobody else can instance it since we are a Singleton.
	 * 
	 * @param	$overrides_ini_path	String	Path to an alternative INI fine that has 
	 * 		mappings that will be used to overwrite the defaults (not replace ALL 
	 * 		completely).
	 */
	private function __construct($overrides_ini_path = array())
	{
		self::$_map = parse_ini_file(DEFAULT_HANDLER_MAP_PATH, true);
		
		if($overrides_ini_path and file_exists($overrides_ini_path))
		{
			$overrides = parse_ini_file($overrides_ini_path);

			self::$_map = array_merge($this->_map, $overrides);
		}
	}
	
	/**
	 * Call this statically to get global instance.
	 * Singleton+Factory method.
	 *
	 * @param	$overrides_ini_path		String
	 * @return 	HandlerMap
	 */
	public static function get($overrides_ini_path = array())
	{
		static $inst = null;
		
		if (is_null($inst))
			$inst = new HandlerMap($overrides_ini_path);

		return $inst;
	}
	
	/**
	 * Lookup the value for a given key in the INI 
	 * 
	 * i.e. the Handler given a file extension
	 *
	 * @return 	String 	
	 **/
	public function lookup($key)
	{		
		if(array_key_exists($key, self::$_map))
		{
			return self::$_map[$key];
		}
		else
			return false;
	}
}
