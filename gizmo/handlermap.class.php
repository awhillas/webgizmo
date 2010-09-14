<?php

/**
 * path to default extension to handler mapping. 
 * File should be in INI format.
 */
if (!defined('DEFAULT_HANDLER_MAP_PATH'))
	define('DEFAULT_HANDLER_MAP_PATH', 	GIZMO_PATH.'/contenttypes/default.map.ini');

/**
 * undocumented class
 *
 * @package Web Gizmo
 * @author Alexander R B Whillas
 **/
class HandlerMap
{
	private static $_map;
	
	/**
	 * Private constructor so nobody else can instance it since we are a Singleton.
	 * 
	 * @param	$overrides_ini_path	String
	 */
	private function __construct($overrides_ini_path = array())
	{
		$this->_map = parse_ini_file(DEFAULT_HANDLER_MAP_PATH);
		
		if($overrides_ini_path and file_exists($overrides_ini_path))
		{
			$overrides = parse_ini_file($overrides_ini_path);
print_r($overrides);die;
			$this->_map = array_merge($this->_map, $overrides);
		}
	}
	
	/**
	 * Call this statically to get global instance.
	 * Singleton Factory method.
	 *
	 * @param	$overrides_ini_path	String
	 * @return 	HandlerMap
	 */
	public static function get($overrides_ini_path = array())
	{
		static $inst = null;
		
		if (is_null($inst))
			$inst = new HandlerMap($overrides_ini_path);

		return $inst;
	}
	
	public function lookup($key)
	{		
		if(array_key_exists($key, $this->_map))
		{
			return $this->_map[$key];
		}
		else
			return false;
	}
}
