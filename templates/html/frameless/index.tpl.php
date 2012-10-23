<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php echo $language ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?php echo $language ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?php echo $language ?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php echo $language ?>"> <!--<![endif]-->
<head>
	<meta charset="<?php echo CHAR_ENCODING ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title><?php echo $pagetitle ?>, <?php echo $title ?></title>
	<meta name="description" content="<?php echo $description ?>">
	
	<meta http-equiv="imagetoolbar" content="false" />
	
	<!-- facebook share link, see: https://developers.facebook.com/docs/share/ -->
	<meta property="og:title" content="<?php echo $pagetitle ?>, <?php echo $title ?>" />
	<meta property="og:description" content="<?php echo $description ?>" />
	
	<meta name="viewport" content="width=device-width">

	<?php echo $head ?>

</head>
<body class="<?php echo $fs->pathCSS() ?>">
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	<!-- NOTE: This is intended as a base template to be built on. It has no layout or styling. -->
	<header>
		<h1><a href="<?php echo $home ?>"><?php echo $title ?></a></h1>
	</header>
	<div role="main">
		<?php echo $content ?>
	</div>
	<nav>
		<?php echo $fs->menu(1, true) ?>
		
		<?php echo $fs->getLanguageLinks() ?>
	</nav>
	<footer role="contentinfo">
		<?php echo $foot ?>
		<p>Powered by <a href="http://gizmo.tsd.net.au">WebGizmo</a>
	</footer>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>

	<?php if(defined('GA_ID')): ?>
		<script>
		  var _gaq=[['_setAccount','<?php echo GA_ID ?>'],['_trackPageview']];
		  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		  s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
	<?php endif ?>


</body>
</html>