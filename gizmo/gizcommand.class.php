<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Command processor interface for use in processing arrays of Files (FSObject)
 *
 * @package WebGizmo
 * @author Alexander Whillas
 **/
class GizCommand
{
//	abstract public static function go($filter, $subject, $param);
	
	public static function getList()
	{
		return array();
	}
	
} // END class GizCommand