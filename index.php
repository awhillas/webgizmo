<?php
	// Change these variables per website
//	define('SITE_TITLE', 'Website name here');
	define('THEME_DIR', '/yui');

	// Leave this alone...
	require dirname(__FILE__).'/gizmo/FS.class.php';
	$cms = new FS();
	echo $cms->HttpRequest();