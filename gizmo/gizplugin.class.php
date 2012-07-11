<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * GizPlugin - A plugin that can define certain hooks and is not 
 * necessarily a content Handler (i.e. FSFile or FSDir).
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 **/
class GizPlugin
{
	// // // // // // // // // // // // // // // // //
	// Hooks
	// // // // // // // // // // // // // // // // //
	
	/**
	 * Called to handle a plugin specific URL i.e. any that are prefixed 
	 * with GIZMO_PLUGIN_URL_PREFIX.'/'.<plugin class>
	 **/
	public function url() {}
	
} // END class 

