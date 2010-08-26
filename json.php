<?php
	// Leave this alone...
	require dirname(__FILE__).'/gizmo/FS.class.php';
	$gizmo = new FS();
	echo $gizmo->HttpRequest('json');