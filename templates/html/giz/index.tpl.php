<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head lang="<?php echo $langauge ?>">
	<title><?php echo $title ?></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHAR_ENCODING ?>">
	<meta name="generator" content="Web Gizmo <?php echo $gizmo_version ?>">
	
	<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
	<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
	
	<link rel="stylesheet" href="http://yui.dev/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
	<link rel="stylesheet" href="http://yui.dev/build/base/base.css" type="text/css">
	
	<link rel="stylesheet" href="<?php echo $templates ?>/style.css" type="text/css">	
	
	<!-- Fonts -->
	<link href="http://kernest.com/fonts/komika-axis.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Droid+Sans&amp;subset=latin" rel="stylesheet" type="text/css">
	
	<?php echo $head ?>
	
</head>
<body>
<div id="doc">
	
	<div id="hd" role="banner">
		<?php echo ul($fs->menu(), 'Menu') ?>
		<h1><a href="<?php echo $home ?>"><?php echo $title ?></a></h1>
	</div>
	
	<div id="bd" role="main">
		
		<div class="yui-b LeadingBlurb">

			<!-- YOUR DATA GOES HERE -->
			
			<?php echo @$top ?>
			
		</div>
				
		<div id="yui-main"><div class="yui-b">
				
				<div class="yui-gb">
					<!-- 3 columns -->
					<div class="yui-u first">
						<?php echo @$owner ?>
					</div> 
					<div class="yui-u">
						<?php echo @$designer ?>
					</div>
					<div class="yui-u">
						<?php echo @$developer ?>
					</div>				
				</div>
				
		</div></div>

		<div class="yui-b">

			<!-- YOUR DATA GOES HERE -->
			
			<?php echo $content ?>
			
		</div>

	</div>
	
	<div id="ft" role="contentinfo">
		<hr />
		<?php echo $foot ?>
		<p>Powered by <a href="http://gizmo.tsd.net.au">WebGizmo</a> and <a href="http://kernest.com">Fonts via Kernest</a></p>
		
	</div>

</div>

</body>
</html>
