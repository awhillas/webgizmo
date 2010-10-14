<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">

<html lang="<?php echo $langauge ?>">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHAR_ENCODING ?>">
	<meta name="generator" content="Web Gizmo">
	
	<title><?php echo $title ?></title>
	
</head>

<body class="<?php echo $fs->pathCSS() ?>">
	<h1><a href="/"><?php echo $title ?></a></h1>
	
	<?php echo ul($fs->menu(), 'Menu') ?>
	
	<?php echo $fs->render() ?>
	
</body>
</html>

