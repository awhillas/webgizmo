<?php
	header('Content-type: text/css');

	require_once dirname(__FILE__) . '/lib/entities.less.class.php';

	$less = new LessCode();
//	$less->setVariable('panes.input.select.hover.bg', 'XXXXXXXXXXXXXX');
//	$less->parseFile($_SERVER['argv'][1]);
	
	$file = $_GET['file'];

	$less->parseFile($file);
	
	echo $less->output();