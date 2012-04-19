<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head lang="<?php echo $language ?>">
	
	<title><?php echo $title ?></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHAR_ENCODING ?>">
	<meta name="generator" content="Web Gizmo <?php echo $gizmo_version ?>">
	
	<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
	<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
	
	<link rel="stylesheet" href="http://yui.dev/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
	<link rel="stylesheet" href="http://yui.dev/build/base/base.css" type="text/css">
	
	<?php echo $head ?>
	
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold&subset=latin' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Droid+Serif:regular,bold&subset=latin' rel='stylesheet' type='text/css'>
	
<!--
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-1056995-4']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>	
-->
</head>
<body class="<?php echo $fs->pathCSS() ?>">

	<div id="doc2" class="yui-t7">
		<div id="hd" role="banner">
			<h1>
				<a href="<?php echo $home ?>"><?php echo $title ?></a>
			</h1>
			<?php echo div(ul($fs->menu(), 'Menu'), null, 'MENU') ?>
			<hr>
		</div>
		<div id="bd" role="main">
			<div class="yui-g">
				
				<!-- YOUR DATA GOES HERE -->
				
				<?php echo $content ?>
				
			</div>
		</div>
		<div id="ft" role="contentinfo">
			
			<?php echo $foot ?>
			
		</div>
	</div>	

</body>
</html>
