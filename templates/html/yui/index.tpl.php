<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="<?php echo $language ?>">
<head>
	<title><?php echo $pagetitle ?>, <?php echo $title ?></title>
	<meta name="description" content="<?php echo $description ?>">
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHAR_ENCODING ?>">
	<meta name="generator" content="Web Gizmo <?php echo $gizmo_version ?>">
	
	<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
	<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
	
	<?php echo $head ?>
	
</head>
<body class="<?php echo $fs->pathCSS() ?>">
	
	<div id="doc" class="yui-t1">
	
		<div id="hd" role="banner">
			<h1><a href="<?php echo $home ?>"><?php echo $title ?></a></h1>
		</div>

		<div id="bd" role="main">
			<div id="yui-main">
				<div class="yui-b"><div class="yui-g">

					<!-- YOUR DATA GOES HERE -->
					<?php echo $content ?>

				</div></div>
			</div>

			<div class="yui-b">
				
				<!-- YOUR DATA GOES HERE -->
				<?php echo $fs->menu(1, true) // depth = 1 & show leafs ?>
				
				<?php echo $fs->getLanguageLinks() ?>
				
			</div>
		</div>
	
		<div id="ft" role="contentinfo">
			<?php echo $foot ?>
			<p>Powered by <a href="http://gizmo.tsd.net.au">WebGizmo</a> a <a href="http://www.tsd.net.au">TSD</a> invention.</p>
		</div>
		
	</div>

	<?php if(defined('GA_ID')): ?>
		<script>
		  var _gaq=[['_setAccount','GA_ID'],['_trackPageview']];
		  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		  s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
	<?php endif ?>

</body>
</html>
