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
	
	<div id="doc3" class="yui-t3">
	
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
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '<?php echo GA_ID ?>']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	<?php endif ?>
</body>
</html>
