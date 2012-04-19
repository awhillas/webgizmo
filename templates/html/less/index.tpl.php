<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title><?php echo $title ?></title>
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<!-- Adding "maximum-scale=1" fixes the Mobile Safari auto-zoom bug: http://filamentgroup.com/examples/iosScaleBug/ -->
		<?php echo $head ?>
		
	</head>
	
	<body lang="<?php echo $language ?>">

		<?php echo $content ?>

	</body>
	
</html>