<?php
	/**
	 * Handles HTTP requests for JSON renderings of a path
	 * 
	 * @package WebGizmo
	 * @author Alexander R B Whillas
	 * @license http://www.gnu.org/copyleft/lesser.html LGPL
	 **/

	// Leave this alone...
	require dirname(__FILE__).'/gizmo/FS.class.php';
	$gizmo = new FS();
	echo $gizmo->HttpRequest('json');