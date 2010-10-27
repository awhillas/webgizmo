<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHAR_ENCODING ?>">
	
	<title><?php echo $title ?></title>
	
</head>

<body>
	<h1><a href="/"><?php echo $title ?></a></h1>
	
	<ul class="Menu">
	<?php foreach ($fs->getMenu() as $link): ?>
		<li><?php echo $link ?></li>
	<?php endforeach ?>
	</ul>
	
	<?php echo $fs->render() ?>
	
</body>
</html>

