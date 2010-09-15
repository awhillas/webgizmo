<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Contains various File (FSObject) array sorting commands
 *
 * @see GizCommand, GizFilter
 * @package WebGizmo
 **/
class GizSorter extends GizCommand
{
	public static function go($sort, $subject, $param)
	{
		return $subject;
	}
	
	public static function getList()
	{
		return array();
	}
	
} // END class GizSorter