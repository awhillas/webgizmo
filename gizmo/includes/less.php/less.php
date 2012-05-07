<?php
	header('Content-type: text/css');

	include dirname(__FILE__).'/lessc.inc.php'
	
	$less = new lessc($_GET['file']);
	echo $less->parse();