<?php
	/**
	 * Handles HTTP Requests for HTML renderings of a path in the content directory.
	 * 
	 * @package WebGizmo
	 * @author Alexander R B Whillas
	 * @license http://www.gnu.org/copyleft/lesser.html LGPL
	 **/

	// Change these variables per website...

	// Name of the website.
	define('SITE_TITLE', 'Website title here');	
	define('SITE_DESCRIPTION', 'A Web Gizmo powered website!');
	
	// Template to use. Name of one of the folders in: /templates/html
	define('THEME_DIR', '/yui');
	// Google Analytics. Must be setup in template. See end of /yui/index.tpl.php
	// define('GA_ID', 'UA-XXXXXXXX-X') 

	//  - - - - - - - - - - - - leave this part alone...

	//  assumes /gizmo folder is in the same folder as this file.
	require dirname(__FILE__).'/gizmo/FS.class.php';	
	echo FS::get()->http();