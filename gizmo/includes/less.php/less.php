<?php
	require_once dirname(__FILE__) . 'entities.less.class.php';

	$less = new LessCode();
//	$less->setVariable('panes.input.select.hover.bg', 'XXXXXXXXXXXXXX');
//	$less->parseFile($_SERVER['argv'][1]);
	$less->parseFile($_GET['file']);
	echo $less->output();