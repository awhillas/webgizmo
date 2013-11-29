<?php
	header('Content-type: text/css');
	
	require dirname(__FILE__).'/gizmo/FS.class.php';
	require INCLUDES_PATH.'/less.php/lessc.inc.php';
	
	// TODO: this might run into trouble if https or Gizmo is not in the root folder.
	//$code = file_get_contents('http://'.$_SERVER["SERVER_NAME"].'/'.$_GET['file']);
	echo WEB_ROOT.$_GET['file'];
	$code = file_get_contents(WEB_ROOT.'/'.$_GET['file']);

	$less = new lessc();
	echo $less->parse($code);