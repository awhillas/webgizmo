<?php
/*
	Web Gizmo Unit Testing
	----------------------
*/

echo '<h1>Web Gizmo: Unit Testing</h1>';


/*
	Configuration for testing
	-------------------------
*/

//define('GIZMO_PATH', 		'/Users/alex/Sites/PLAY/alexander.whillas.com/gizmo');
define('REALPATH_CKECKING', false);	// So sym. link to Gizmo works
define('DEBUG', true);

// Standard Include in all index.php files
require dirname(__FILE__).'/gizmo/FS.class.php';

// Instanciate system
$fs = FS::get();

function is($boolean) {
	if($boolean)
		return ' <span style="color: green">YES!</span><br>';
	else
		return ' <span style="color: red">NO?</span><br>';
}

function test_url($url)
{
	$h = get_headers($url, 1);
	
	if(isset($h['Location']) && preg_match('/Moved Permanently$/', $h[0]))
		return test_url($h['Location']);
	
	return preg_match('/200 OK$/', $h[0]);
}

/*
	Path Testing
	----------------------
*/


echo 'Gizmo is subpath of Web Root:'.is(Path::open(GIZMO_PATH)->isChildOf(WEB_ROOT));
echo 'Real path checking: '.is(REALPATH_CHECKING);

$paths = array(
	'Content'	=> $fs->contentRoot(),
	'Templates'	=> $fs->templatesRoot(),
	'Plugins'	=> $fs->pluginsRoot()
);

$base = 'http://'.$_SERVER["HTTP_HOST"];
foreach($paths as $name => $p)
{
	echo '<h2>'.$name.' path: '.$p->get().'</h2>';
	echo 'Readable: '.is(is_readable($p->get()));
	echo 'Exists: '.is(file_exists($p->get()));
	
	$url = $base.$p->realURL();
	echo 'Real URL: '.a($url).'<br>';
	echo 'Accessible: '.is(test_url($url));
	if(!test_url($url)) pr($h);

	if($name == 'Content')
	{
		$url = $base.$p->url();
		echo 'Virtual URL: '.a($url).'<br>';
		echo 'Accessible: '.is(test_url($url));
		if(!test_url($url)) pr($h);
	}
}

defcon();
