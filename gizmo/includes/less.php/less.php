<?php
	header('Content-type: text/css');

	include dirname(__FILE__).'/lessc.inc.php';
	// TODO: this might run into trouble if https or Gizmo is not in the root folder.
	$code = file_get_contents('http://'.$_SERVER["SERVER_NAME"].$_GET['file']);
	$less = new lessc();
	echo $less->parse($code);