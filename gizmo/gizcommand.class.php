<?php
/**
 * Command processor interface for use in processing arrays of Files (FSObject)
 *
 * @package Web Gizmo
 * @author Alexander Whillas
 **/
abstract class GizCommand
{
	abstract public static function go($filter, $subject, $param);
	
	public static function getList()
	{
		return array();
	}
	
} // END class GizCommand