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
	
	<!-- Fonts -->
	<link href="http://kernest.com/fonts/komika-axis.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Droid+Sans&amp;subset=latin" rel="stylesheet" type="text/css">
	
	<?php echo $head ?>
	
	<style>
		html, body { 
			font-family: 'Droid Sans', Georgia, serif;
			color: #555;
			line-height: 2;
			letter-spacing: 1px;
		}

		h1, h2, h3, h4 {
			font-family: 'Komika Axis', cursive;
			line-height: 140%;
			text-rendering: optimizeLegibility;

			letter-spacing: 0;
			font-size: 2em;
			line-height: 1; 
		}
		h1 {
			font-size: 3em;
			line-height: 1;
		}
		a {  }
		dt {
			font-family: "Comic Sans MS", cursive;
			line-height: 1.5;
/*			letter-spacing: 0.1em;*/
			font-size: 1.2em;
		}
		ol, ul { margin-left: 1.4em; }
		dl {
			margin-left: 0;
		}
		em {
			background-color: yellow;
		}
		strong, dt, h1, h2, h3, h4 {
			color: black;
		}
		a 		{ text-decoration: none; }
		a:hover { text-decoration: underline; }
		
		ul.Menu {
			margin: 0.5em 0;
			overflow: auto;
			width: 100%
		}
		a.FSDir,
		ul.Menu li {
			float: left;
			margin-right: 1em;
			font-size: 1em;
			list-style: none;
		}
		a.FSDir,
		ul.Menu li a {
			font-family: "Comic Sans MS", cursive;
		}
	</style>
	
</head>
<body>
<div id="doc">
	
	<div id="hd" role="banner">
		<?php echo ul($fs->menu(), 'Menu') ?>
		<h1><a href="/"><?php echo $title ?></a></h1>
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
		<?php echo $foot ?>
		<p>Powered by <a href="http://gizmo.tsd.net.au">WebGizmo</a> and <a href="http://kernest.com">Fonts via Kernest</a></p>
		
	</div>

</div>

</body>
</html>
