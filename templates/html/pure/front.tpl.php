<!doctype html>
<html lang="<?php echo $language ?>">
<head>
    <meta charset="u<?php echo CHAR_ENCODING ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo SITE_DESCRIPTION ?>">

    <title><?php echo $pagetitle ?>, <?php echo $title ?></title>

    <!-- <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css"> -->

    <!--[if lte IE 8]>
        <link rel="stylesheet" href="<?php echo $templates ?>/layouts/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="<?php echo $templates ?>/css/layouts/side-menu.css">
    <!--<![endif]-->

    <?php echo $head ?>

    <style>
    .content {
      max-width: 100%
    }
    .pure-g > div {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .l-box {
        padding: 1em;
    }
    </style>

</head>
<body class="<?php echo $fs->pathCSS() ?>">

<div id="layout">
    <!-- Menu toggle -->
    <a href="#menu" id="menuLink" class="menu-link">
        <!-- Hamburger icon -->
        <span></span>
    </a>

    <div id="menu">
        <div class="pure-menu pure-menu-open">
            <a href="<?php echo $home ?>"><?php echo $title ?></a>

              <?php echo $fs->menu() ?>

              <?php echo $fs->getLanguageLinks() ?>

        </div>
    </div>

    <div id="main">
        <div class="header">
            <h1><?php echo $pagetitle ?></h1>
            <h2><?php echo $title ?></h2>
        </div>

        <div class="content">

          <div class="pure-g">
              <div class="pure-u-sm-1 pure-u-md-1-3 l-box"><p><?php echo $left ?></p></div>
              <div class="pure-u-sm-1 pure-u-md-1-3 l-box"><p><?php echo $content ?></p></div>
              <div class="pure-u-sm-1 pure-u-md-1-3 l-box"><p><?php echo $right ?></p></div>
          </div>

          <?php echo $foot ?>

          <p>Powered by <a href="http://gizmo.tsd.net.au">WebGizmo</a> a <a href="http://www.tsd.net.au">TSD</a> invention.</p>

        </div>
    </div>
</div>

<script src="<?php echo $templates ?>/js/pure/ui.js"></script>

<?php include PLUGINS_PATH.'/googleAnalytics.php'?>

</body>
</html>
