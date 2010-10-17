<?php
	/**
	 * Handles HTTP Requests for HTML renderings of a path in the content directory.
	 * 
	 * @package WebGizmo
	 * @author Alexander R B Whillas
	 * @license http://www.gnu.org/copyleft/lesser.html LGPL
	 **/

	// Change these variables per website
//	define('SITE_TITLE', 'Website name here');
//		define('THEME_DIR', '/yui');

	// Leave this alone...
	require dirname(__FILE__).'/gizmo/FS.class.php';
	
//	echo FS::get()->HttpRequest();
	echo FS::get()->http();