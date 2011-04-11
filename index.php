<?php
	/**
	 * Handles HTTP Requests for HTML renderings of a path in the content directory.
	 * 
	 * @package WebGizmo
	 * @author Alexander R B Whillas
	 * @license http://www.gnu.org/copyleft/lesser.html LGPL
	 **/

	// Change these variables per website
	define('SITE_TITLE', 	'Web Gizmo');	// Name of the website.
	define('THEME_DIR', 	'/giz');		// Template top use. i.e. /templates/html/giz
	define('FOLDER_DISPLAY', 'links');	// How folders are handled. Can be 'links', 'teaser' or 'none'

	// Leave this alone... assumes /gizmo folder is in the same folder as this file.
	require dirname(__FILE__).'/gizmo/FS.class.php';
	
	echo FS::get()->http();