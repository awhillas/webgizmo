<?php
	/**
	 * Handles HTTP Requests for HTML renderings of a path in the content directory.
	 * 
	 * @package WebGizmo
	 * @author Alexander R B Whillas
	 * @license http://www.gnu.org/copyleft/lesser.html LGPL
	 **/

	// Change these variables per website
	define('SITE_TITLE', 	    'Website title here');	// Name of the website.
	define('THEME_DIR',       '/yui');		// Template to use. Name of one of the folders in: /templates/html
	define('FOLDER_DISPLAY',  'none');	// How subfolders are handled. Can be 'links', 'teaser' or 'none'
	
	define('DEBUG', false);	// set to True to see errors.

	// Leave this alone... assumes /gizmo folder is in the same folder as this file.
	require dirname(__FILE__).'/gizmo/FS.class.php';
	
	echo FS::get()->http();
