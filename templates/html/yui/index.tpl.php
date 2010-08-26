<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head lang="<?php echo $langauge ?>">
	<title><?php echo $title ?></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHAR_ENCODING ?>">
	
	<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
	<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
</head>
<body>
<div id="doc" class="yui-t1">
   <div id="hd" role="banner"><h1><a href="<?php echo BASE_URL_PATH ?>"><?php echo $title ?></a></h1></div>
   <div id="bd" role="main">
	<div id="yui-main">
	<div class="yui-b"><div class="yui-g">
		
		<!-- YOUR DATA GOES HERE -->
	
		<?php echo $fs->render('files') ?>

	</div>
</div>
	</div>
	<div class="yui-b">
		
		<!-- YOUR DATA GOES HERE -->
		
		<?php echo ul($fs->getMenu(), 'Menu') ?>	
	
	</div>
	
	</div>
	<div id="ft" role="contentinfo">
		<p>Powered by <a href="http://gizmo.tsd.net.au">WebGizmo</a></p>
	</div>
</div>


</body>
</html>
