<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="<?php echo $language ?>">
<head>
	<title><?php echo $pagetitle ?>, <?php echo $title ?></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHAR_ENCODING ?>">
	<meta name="generator" content="Web Gizmo <?php echo $gizmo_version ?>">
		
	<?php echo $head ?>
	
</head>
<!-- Some classes based on path to make page specific styling easier.  -->
<body class="<?php echo $fs->pathCSS() ?>">
	
	<div id="doc">
	
		<div id="hd" role="banner">
		
			<div id="SITE_TITLE"><a href="<?php echo $home ?>"><?php echo $title ?></a></div>
		
			<!-- Menu's are easy. $fs is the global "File System" object which makes the folder links.  -->
			<!-- ul() is part of the simple HTML markup lib with Gizmo with functions names after tags.  -->
			<?php echo $fs->menu() ?>

		</div>
	
		<div id="bd" role="main">
		
			<div class="yui-b LeadingBlurb">

				<!-- Content (files/folders) tagged with top (i.e. file name starts with '_top_')  -->
				<!-- will get inserted here. The @ hides errors when there is nothing with tag in  -->
				<!-- the current path.  -->
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

				<!-- Anything thats not tagged and doesn't start with an underscore ends up here. -->
				<?php echo $content ?>
			
			</div>

		</div>
	
		<div id="ft" role="contentinfo">
			<hr />
			<?php echo $foot ?>
			<p>Powered by <a href="http://gizmo.tsd.net.au">Gizmo</a> a <a href="http://www.tsd.net.au">TSD</a> invention.</p>
		</div>

	</div>

	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-1058558-9']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>

</body>
</html>
