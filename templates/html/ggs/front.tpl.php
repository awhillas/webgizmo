<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php echo $language ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?php echo $language ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?php echo $language ?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php echo $language ?>"> <!--<![endif]-->
	<head>
		<meta charset="<?php echo CHAR_ENCODING ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title><?php echo $title ?></title>
		<meta name="description" content="<?php echo $description ?>">
		
		<!-- Please don't add "maximum-scale=1" here. It's bad for accessibility. -->
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php echo $head ?>
		
	</head>
	
	<body lang="en">
		
		<!-- Demo code begins -->
		
		<header>
			<div class="wrapper">
				<h1>Web Gizmo</h1>
				<h2>
					A web system with a focus on simplicity and flexibility. Fast to setup an easy to maintain.
				</h2>
			</div>
		</header>
		
		<article id="twoway">
			<section class="wrapper">
				<h3>A two-way split</h3>
				<p>
					These two blocks of text will float side by side with some empty columns on the sides on large screens. On medium-sized screens the empty columns will disappear, and on small screens the blocks will be stacked vertically.
				</p>
			</section>
			<section class="wrapper">
				<h3>By the way</h3>
				<p>
					If you're viewing this page on an iOS device, it might zoom in wonkily when you rotate your device. This is because of <a href="http://filamentgroup.com/examples/iosScaleBug/">a Mobile Safari bug</a>.
				</p>
			</section>
		</article>
		
		<!-- Demo code ends -->
			
	</body>

	<?php include PLUGINS_PATH.'/googleAnalytics.php'?>
	
</html>