<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

// // // // // // // // // // // // // // // // //
// Path constants
// // // // // // // // // // // // // // // // //

// Can be overriden in the index.php file so can be shared by multipule websites.
// @todo perhap wrap these up in a Class?

/**
 * @global	string	
 */
if (!defined('WEB_ROOT')) 		define('WEB_ROOT', 		dirname($_SERVER['SCRIPT_FILENAME'])); 
/**
 * @global	string	
 */
if (!defined('GIZMO_PATH'))		define('GIZMO_PATH', 	dirname(__FILE__));
/**
 * @global	string	
 */
if (!defined('INCLUDES_PATH'))	define('INCLUDES_PATH',	GIZMO_PATH.'/includes');
/**
 * @global	string	
 */
if (!defined('PLUGINS_PATH'))	define('PLUGINS_PATH',	GIZMO_PATH.'/plugins');
/**
 * @global	string	
 */
if (!defined('CONTENT_DIR')) 	define('CONTENT_DIR', 	'/content');
/**
 * @global	string	
 */
if (!defined('TEMPLATES_DIR'))	define('TEMPLATES_DIR',	'/templates');

// // // // // // // // // // // // // // // // //
// Behaviour Settings
// // // // // // // // // // // // // // // // //

/**
 * @global	string	
 */
if (!defined('DEFAULT_START'))	define('DEFAULT_START',	'/');

/**
 * mod_rewrite is on.
 * @global	Boolean
 */
if (!defined('REWRITE_URLS'))	define('REWRITE_URLS', true);
/**
 * If gizmo is not in the root then add this to base URL i.e. beginning of all link URLs
 * @see FS::getURL()
 * @global	String
 */
if (!defined('BASE_URL_PATH'))	define('BASE_URL_PATH', '/');

/**
 * @global	string	
 */
if (!defined('MULTI_LINGUAL'))	define('MULTI_LINGUAL',	false);
/**
 * @global	string	
 */
if (!defined('DEFAULT_LANGUAGE'))	define('DEFAULT_LANGUAGE',	'en');

/**
 * Character set used, used for htmlentities() function and HTML document header
 * @global	String
 * @see http://php.net/manual/en/function.htmlentities.php
 * @see FS::strip()
 **/
if (!defined('CHAR_ENCODING'))	define('CHAR_ENCODING',	'UTF-8');

/////// Content Variables...
/**
 * @global	string	
 */
if (!defined('SITE_TITLE'))		define('SITE_TITLE', 	$_SERVER['SERVER_NAME']);
/**
 * @global	string	
 */
if (!defined('THEME_DIR'))		define('THEME_DIR',		'/default');

/**
 * The name of the default render class
 * @global	String
 */
if (!defined('CONTENT_RENDERER'))	define('CONTENT_RENDERER', 'BasicRender');

// // // // // // // // // // // // // // // // //
// Boring stuff...
// // // // // // // // // // // // // // // // //

// Check PHP version is high enough
if(version_compare(PHP_VERSION, '5.0.0') <= 0)
	trigger_error('You need at least PHP 5.0 to use Web Gizmo! You have version '.PHP_VERSION, E_ERROR);

// Make sure our Classes get included automatically
set_include_path(
	implode(PATH_SEPARATOR, array(get_include_path(), GIZMO_PATH, GIZMO_PATH.'/contenttypes', PLUGINS_PATH))
);
spl_autoload_extensions('.class.php');
spl_autoload_register();	// Use default autoload implementation coz its fast

/**
 * HTML tag rendering functions library.
 */
include 'includes/html.php';
include 'includes/krumo/class.krumo.php';

/**
 * FS class
 * 
 * Encapsulates all the File System (FS) details for Gizmo.
 * It was called FS as Gizmo was original called FS-CMS.
 * Is a Singleton, with the static get() method. 
 *
 * @package	WebGizmo
 * @todo Put some more Exception throwing + handling in
 */
class FS
{
	/**
	 * Stactic copy of self. Used in the Singleton design pattern.
	 * @var FS
	 */
	private static $_instance;
	
	/**
	 * List of files to ignore
	 * Would be nice if arrays could be in Class Constants :(
	 * @var Array
	 */
	private $EXCLUDE_FILES = array('.', '..', '.DS_Store', 'Thumbs.db');
	
	/**
	 * Array of Content objects
	 * @var Array
	 */
	private $content = Array();
	
	/**
	 * Path to the content directory
	 * @var Path 
	 */
	private $path;
	
	/**
	 * @var Path
	 */
	private $contentRoot;
	
	/**
	 * @var	Path
	 */
	private $templatesRoot;
	
	private function __construct($content_path = CONTENT_DIR, $templates_path = TEMPLATES_DIR)
	{
		// __set() should turn these into Path objects and store them in $this->_paths. uber nur PHP5.3 
		$this->contentRoot = (MULTI_LINGUAL)
			? new Path(WEB_ROOT . $content_path. '/'.$this->getLanguage(), true)
			: new Path(WEB_ROOT . $content_path, true);

		// Current Content path
		$this->path = new Path($this->contentRoot->get() . FS::getPath()->get());
		
		// If we're on the DEFAULT_START path then treat as the new root path	
		if(!FS::getPath()->get() && DEFAULT_START != '/')
		{
			$this->path = new Path(WEB_ROOT . $content_path . DEFAULT_START, true);
		}
		
		// Templates path
		
		$this->templatesRoot = new Path(WEB_ROOT . $templates_path, true);
	}

	/**
	 * Singleton constructor/factory method.
	 *
	 * @return 	FS	Global instance of the FS object
	 **/
	public static function get($content_path = CONTENT_DIR, $templates_path = TEMPLATES_DIR) 
	{
        if (!self::$_instance)
        {
            self::$_instance = new FS($content_path, $templates_path);
        }
        return self::$_instance;
	}
	
	/**
	 * So when this object is used as a function the 'parse' method is invoked
	 * Handy in the Savant templates where an instance of FS is already instantiated
	 * 
	 * @see FS::parse()
	 * @param	Array	of SplFileInfo objects
	 * @return 	Array	Array of FileContent
	 */
	public function __invoke($files_list) 
	{
		return $this->parse($files_list);
	}
	
	/**
	 * Build array of FileContent objects from an Array of FileInfo objects.
	 * 
	 * @param	Array	of SplFileInfo objects
	 * @return 	Array	Array of FileContent
	 */
	public function parse($files_list)
	{
		$out = array();

		foreach ($files_list as $File)	
		{	
			$filename = $File->getFilename();
			
			if (!in_array($filename, $this->EXCLUDE_FILES) and preg_match('/^[^_]/', $filename))
			{
				$out[$filename] = FileContent::Factory($File);
			}
		}		
		return $out;
	}

	/**
	 * @return Path object
	 */
	public function templatePath($format = 'html')
	{
		return new Path($this->templatesRoot->get() .'/'. $format . THEME_DIR);
	}
	
	/**
	 * Getter for the content path
	 *
	 * @return String
	 **/
	public function contentPath()
	{
		return $this->path;
	}
	
	/**
	 * Getter for the content root/base path
	 *
	 * @return String
	 * @author Alexander Whillas
	 **/
	public function contentRoot()
	{
		return $this->contentRoot;
	}
	
	/**
	 * Getter for the root path to the content directory
	 *
	 * @return String
	 **/
	public function templatesRoot()
	{
		return $this->templatesRoot;
	}

	/**
	 * Instantiate the Template Engine and find all the appropriate templates to give it.
	 * 
	 * @see http://phpsavant.com/
	 * @see http://devzone.zend.com/article/9075
	 */
	private function getTemplate($format = 'html')
	{
		$Path = $this->templatePath($format);
		
		if($Path->is())
		{
			// Using Savant3 template system.
			require INCLUDES_PATH.'/Savant3/Savant3.php';

			// set options
			$options = array(
				'template_path' => $Path,
				'exceptions'    => true,
				'extract'       => true
			);

			// initialize template engine
			return new Savant3($options);
		}
		else
		{
			trigger_error('Template path could not be found: '.$Path, E_USER_ERROR);
			return false;
		}
	}

	/**
	 * Handle HTTP request
	 * 
	 * @return 	String	Content in the form of the requested $format
	 * 
	 * @todo Send some HTTP headers here with the right MIME type and cacheing info...?
	 * @todo Lookup the correct template to use instead of hard coding 'index.tpl.php'
	 */
	public function HttpRequest($format = 'html')
	{
		if($this->path->is())
		{
			if($tpl = $this->getTemplate($format))
			{
				$tpl->format = $format;
				$tpl->fs = &$this;
				$tpl->here = &$this->path;
				$tpl->title = SITE_TITLE;
				$tpl->langauge = $this->getLanguage();

				$tpl->display('index.tpl.php');
			}
		}
	}
	
	/**
	 * @param	$depth	Integer	Depth that the menu should render. i.e. list in a list.
	 * @return 	Array	List of html links to the top level Content Directories.
	 * @todo Add a base path here so menus starting at a sub path are possible.
	 */
	public function getMenu($depth = 1)
	{
		$out = array();

		foreach (array_keys($this->getContentTree('', $depth)) as $dir) 
		{
			if(preg_match("/^[^_]/", $dir) != 0)
			{
				$url = FS::getURL($dir);

				$class = $this->pathCSS($dir);
				$class .= ('/'.$dir == FS::getPath()) ? ' Selected' : '';

				$out[] = "<a href=\"$url\" class=\"$class\">{$this->clean($dir)}</a>";				
			}
		}

		return $out;
	}
	
	/**
	 * mod_rewrite aware i.e. checks the REWRITE_URLS global
	 * 
	 * @param	$dir	String|SplFileInfo
	 * @return 	String	the correct URL for links given a virtual path.
	 * @see REWRITE_URLS
	 */
	static public function getURL($dir)
	{
		if(is_a($dir, 'SplFileInfo'))
		{
			// Figure out its URL by subtracking the web root
			if($dir->isDir())
				$from = WEB_ROOT . CONTENT_DIR;
			else
				$from = WEB_ROOT;
			
			$dir = Path::open($from)->from($dir->getPathname());
		}
		
		return (REWRITE_URLS) 
			? ((BASE_URL_PATH)? BASE_URL_PATH .'/' : '') . "$dir" // force objects __toString()
			: BASE_URL_PATH . "?path=$dir";	
	}
	
	/**
	 * @return 	String	Value of the CGI "path" variable i.e. the "virtual path"
	 */
	public function vpath()
	{
		return $this->getPath()->get();
	}

	/**
	 * Get the Content tree starting at the given virtual path
	 * 
	 * @param	String	Virtual content path to start from. Should begin with a '/'.
	 * @param	Boolean	Only return Directories, ignore files?
	 * @return 	Array	Multidimensional array representing the directory structure.
	 **/
	public function getContentTree($virtual_root = '', $depth = 1, $only_dirs = true)
	{
		return FS::getDirectoryTree($this->contentRoot.$virtual_root, $depth, $only_dirs);
	}
	
	
	// / / / / / / / / / / / / / / / / / / / / / / / / /
	// Static funcitons
	// / / / / / / / / / / / / / / / / / / / / / / / / /

	/**
	 * Strip leading numbers + underscore from the given string
	 * + escape HTML special characters
	 * 
	 * @return 	String
	 * 
	 * @todo make this work (with reg. expr.'s?)
	 */
	public static function clean($value = '')
	{
		// Strip 00_ from the beginning of a file name
		$value = preg_replace('/^[0-9]{2}[_]{1}/', '', $value);
		
		return htmlentities($value, ENT_NOQUOTES, CHAR_ENCODING);
	}
	
	/**
	 * 'bare bones' recursive method to extract directories and files
	 * 
	 * @param	String	Directory to start scanning from
	 * @param	Boolean	Only return Directories, ignore files?
	 * @return 	Array	
	 * @author	Dustin
	 * @see http://de2.php.net/manual/en/function.scandir.php#88006
	 **/
	public static function getDirectoryTree($outerDir, $depth = 1, $only_dirs = true)
	{
		$dirs = array_diff( scandir( $outerDir ), Array( '.', '..' ) );
		
		$out = Array();
		
		foreach( $dirs as $d )
			if( is_dir($outerDir.'/'.$d) and $depth )
			{
				$out[ $d ] = FS::getDirectoryTree( $outerDir.'/'.$d, $depth - 1);
			} 
			elseif(!$only_dirs)
			{
				$out[ $d ] = $d;
			}
		
		return $out;		
	}

	/**
	 * Get a list of language codes the content is available in.
	 * Look at the top level of the Content folder and assume 
	 * the first level is a list of language codes.
	 *
	 * @return Array
	 **/
	function getContentLanaguages()
	{
		return array_keys(FS::getDirectoryTree(WEB_ROOT.CONTENT_DIR));
	}
	
	/**
	 * Determine the Language of the content
	 *
	 * @return String	ISO639 Two-letter primary language code
	 * @link http://www.w3.org/TR/REC-html40/struct/dirlang.html
	 **/
	function getLanguage()
	{
		$available = $this->getContentLanaguages();
		
		// If 'lang' has been set via GET
		if(isset($_GET['lang']) and in_array($_GET['lang'], $available))
		{
			setcookie('lang', $_GET['lang']);

			return $_GET['lang'];
		}
		
		// Has the language been set in a cookie?
		if(isset($_COOKIE['lang']) and in_array($_COOKIE['lang'], $available))
		{
			return $_COOKIE['lang'];
		}
		
		// Try and sniff the user agent langauge 
		if ( isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"] ) )
		{
			// example: ' fr-ch;q=0.3, da, en-us;q=0.8, en;q=0.5, fr;q=0.3' or 'en-US,en;q=0.8'
			foreach(explode(',', strtolower( $_SERVER["HTTP_ACCEPT_LANGUAGE"] )) as $code)
			{
				$lang = substr( trim($code), 0, 2 ); // take just the 1st two char's
				
				if(in_array($lang, $available))
				{
					return $lang;
				}	
			}
		}
		
		// Is there a default set?
		if(DEFAULT_LANGUAGE)
		{
			return DEFAULT_LANGUAGE;
		}
		
		// No idea so just choose the last one available
		return end($available);
	}
	
	/**
	 * Get the CGI virtual 'path' variable value
	 *
	 * @return String	The current virtual path (relative to the base URL)
	 * @todo make this a gett'r for a Singleton
	 * @todo rename to reflect that its a virtual URL _not_ a system path
	 */
	public static function getPath()
	{		
		return (isset($_GET['path'])) 
			? new Path($_GET['path'])
			: new Path('/');
	}
	
	/**
	 * Make CSS classes specific to the current path for use in the HTML <body> tag for example
	 * 
	 * @return 	String
	 */
	public function pathCSS($path = null)
	{		
		$path = (is_null($path))
			? $this->getPath()->get()
			: $path;
				
		// Build Classes based on Path parts names
		if($path)
		{
			foreach(explode('/', $path) as $folder)
			{
				if(!empty($folder))
					$out[] = str_replace(
						' ', '', 
						ucwords(
							preg_replace('/[^0-9A-Za-z]/', ' ', $this->clean($folder))
						)
					);
			}
			
			$out = implode(' ', $out);

			// Figureout depth

			$out .= ' Depth'.(count(explode('/', $path)) - 1);
			
			return $out;
		}
		else
			return 'Top';	// Homepage
	}
	
	/**
	 * @return String	Rendered content in the given format
	 **/
	public function getContent($format, $query = '')
	{
		return GizLayoutor::make($format)->render($this->path);
	}
	
	/**
	 * Default render the current Virtual path's folder contents.
	 * 
	 * This is more a connivence function and is more of an example. More complex 
	 * filtering and rendering control can be achieved by doing this in the template
	 * itself, and is in fact the idea behind the template design.
	 * 
	 * For example: one might want to filter out everything except images and use only
	 * their paths in some Flash gallery widget one part if the template and then grab only
	 * the .text files and use the default rendering on another part (column say).
	 * 
	 * @param	String	$only	Filter out everything except 'files' or 'folders' 
	 * @return 	String	HTML
	 * @todo 	Replace this with FS::getContent()
	 * @deprecated Since query language came into effect. Use FS::getContent() instead.
	 **/
	public function render($only = 'files', $format = 'html')
	{
		$out = $filter = '';
		
		// Build filter array. See Path::getIt() for more complex examples. 
		switch($only)
		{
			case 'files':
				$filter = array('isFile' => true);
				break;
			
			case 'folders':
				$filter = array('isDir' => true);
				break;
		}
		
		$stupid = CONTENT_RENDERER;
		$Renderer = new $stupid($this->getContent($filter));
		
		// Get the content and render each
		// foreach($this->getContent($filter) as $file) 
		// 	$out .= $file->render($format);
		
		return $Renderer->render($format);
	}
}

