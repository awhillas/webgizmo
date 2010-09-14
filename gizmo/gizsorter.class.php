<?php
/**
 * Contains various File (FSObject) array sorting commands
 *
 * @see GizCommand, GizFilter
 * @package Web Gizmo
 * @author Alexander Whillas
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