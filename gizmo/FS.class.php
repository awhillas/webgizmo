<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

// // // // // // // // // // // // // // // // //
// Basic includes
// // // // // // // // // // // // // // // // //

/**
 * HTML tag rendering functions library.
 */
include 'includes/html.php';
/**
 * Handy debugging functions
 */
include 'includes/debug.php';
/**
 * Nice data dump output.
 */
include 'includes/krumo/class.krumo.php';

/**
 * @global	String	Version number of this install of Web Gizmo
 */
if (!defined('GIZMO_VERSION'))	define('GIZMO_VERSION', '0.2beta');

// // // // // // // // // // // // // // // // //
// Path constants
// // // // // // // // // // // // // // // // //

// Can be overriden in the index.php file so can be shared by multipule websites.
// Nameing conventions for the various PATH/DIRs, names ending in:
// 		_PATH = Server absolute paths
// 		_DIR = Name of single directory (not a full path)

/**
 * @global	string	Server side absolute path to the "web root", where the index.php file is and the request is being served from.
 */
if (!defined('WEB_ROOT')) 		define('WEB_ROOT', 		dirname($_SERVER['SCRIPT_FILENAME'])); 
/**
 * @global	string	Server path to the instillation of Gizmo i.e. where this file is.
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
 * Assumed to be within the WEB_ROOT.
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
 * @global	string	Sub-path of the content folder to redirect to if you don't want the root folder to be it.
 */
if (!defined('DEFAULT_START'))	define('DEFAULT_START',	'/');

if (!defined('REWRITE_URLS'))
{
	// Try to detect if mod_rewrite is installed and the .htaccess is setup correctly.
	if (isset($_SERVER['HTTP_MOD_REWRITE']) AND $_SERVER['HTTP_MOD_REWRITE'] == 'On')
		$isModRewriteOn = true;
	else
		$isModRewriteOn = false;

	/**
	 * @global	Boolean	Is Apache's mod_rewrite active? This is attempted to be 
	 * 		auto-detected and set. Define in index.php to force on or off.
	 * @link http://christian.roy.name/blog/detecting-modrewrite-using-php#comment-170
	 */		
	define('REWRITE_URLS', $isModRewriteOn);
}


/**
 * If gizmo is not in the root then add this to base URL i.e. beginning of all link URLs
 * @see FS::getURL()
 * @global	String
 */
if (!defined('BASE_URL_PATH'))	define('BASE_URL_PATH', '');

/**
 * @global	Boolean		Is the Content folder divided into languages at the root level?
 */
if (!defined('MULTI_LINGUAL'))	define('MULTI_LINGUAL',	false);
/**
 * @global	string		Which language should be the default language if the browser sniffed one isn't present? Should be an ISOxxxx code
 */
if (!defined('DEFAULT_LANGUAGE'))	define('DEFAULT_LANGUAGE',	'en');

/**
 * @global	String	Character set used, used for htmlentities() function and HTML document header
 * @link http://php.net/manual/en/function.htmlentities.php
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
 * @global	string	Overrider of the default HTML layout render'er (Layoutor)	
 */
if (!defined('HTML_LAYOUT'))	define('HTML_LAYOUT',	'LayoutHTML');



// // // // // // // // // // // // // // // // //
// Boring stuff...
// // // // // // // // // // // // // // // // //

// Check PHP version is high enough
if(version_compare(PHP_VERSION, '5.0.0') <= 0)
	trigger_error('You need at least PHP 5.0 to use Web Gizmo! You have version '.PHP_VERSION, E_ERROR);

// Make sure our Classes get included automatically
set_include_path( implode(PATH_SEPARATOR, array(get_include_path(), GIZMO_PATH, GIZMO_PATH.'/handlers', PLUGINS_PATH)) );
spl_autoload_extensions('.class.php');
spl_autoload_register();	// Use default autoload implementation coz its fast


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
	 * Current absolute content path
	 * @var Path
	 * @see FS::currentPath()
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

	/**
	 * @var	Array	Array of content variables to be added to the Template
	 * 		before rendering.
	 * @see FS::add()
	 */
	public $content;
	
	/**
	 * @param	String	Content path override, should be the absolute path to 
	 * 		the content folder on the server. Default is WEB_ROOT + CONTENT_DIR
	 * @param	String	Templates path override, should be the absolute path to 
	 * 		the templates root folder on the server. Default is WEB_ROOT + TEMPLATES_DIR
	 */
	private function __construct($content_path = '', $templates_path = '')
	{
		$content_path 	= (empty($content_path)) 	? WEB_ROOT . CONTENT_DIR					: $content_path;
		$content_path 	= (MULTI_LINGUAL) 			? $content_path . '/'.$this->getLanguage() 	: $content_path;

		$templates_path = (empty($templates_path))	? WEB_ROOT . TEMPLATES_DIR					: $templates_path;
		
		// __set() should turn these into Path objects and store them in $this->_paths. uber nur PHP5.3 
		$this->contentRoot = new Path($content_path, true);

		$this->templatesRoot = new Path($templates_path, true);

		// Current Content path

		$this->path = new Path($content_path . FS::getPath());

		// If we're on the DEFAULT_START path then treat as the new root path	
		if(!FS::getPath()->get() && DEFAULT_START != '/')
		{
			$this->path = new Path($content_path . DEFAULT_START, true);
		}
		
		$this->content = array('head' => '', 'foot' => '');
	}

	/**
	 * Singleton constructor/factory method.
	 *
	 * @return 	FS	Global instance of the FS object
	 **/
	public static function get($content_path = '', $templates_path = '') 
	{		
        if (!self::$_instance)
        {
            self::$_instance = new FS($content_path, $templates_path);
        }
        return self::$_instance;
	}
	
	/**
	 * So when this object is used as a function it returns the current content Path.
	 * Handy in the Savant templates where an instance of FS is already instantiated
	 * 
	 * @see FS::parse()
	 * @return 	Path	Array of FileContent
	 * @todo DO something else here. Parse is deprecisted
	 */
	public function __invoke() 
	{
		return $this->get();
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
	public function currentPath()
	{
		return $this->path;
	}
	
	/**
	 * Getter for the content root/base path
	 *
	 * @return String
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
				$tpl->title = htmlentities(SITE_TITLE);
				$tpl->langauge = $this->getLanguage();

				$tpl->display('index.tpl.php');
			}
		}
	}
	
	/**
	 * Process a HTTP request returning the requested format.
	 *
	 * @return String
	 * @todo 	add some more complex look up of template file name.
	 **/
	public function http($format = 'html')
	{
		if($this->path->is())
		{
			// Get the Layoutor for the format
			$Layout = GizLayoutor::make($format);
			
			// Get the content parts
			$content = $Layout->render($format);

			// Get the template for the format
			if($tpl = $this->getTemplate($format))
			{
				// Standard Gizmo stuff...
				$tpl->format = $format;
				$tpl->fs = &$this;
				$tpl->here = &$this->currentPath();
				$tpl->templates = $this->templatesRoot()->realURL();
				$tpl->title = htmlentities(SITE_TITLE);
				$tpl->langauge = $this->getLanguage();
				$tpl->gizmo_version = GIZMO_VERSION;

				// Shared global vars used by plugins and handlers.
				$tpl->assign($this->content);
				
				// Assign the rendered content to the template
				$tpl->assign($content);

				// Send HTTP headers

				// output the renered content. 
				$tpl->display('index.tpl.php');
			}			
			
		}
	}
	
	/**
	 * @return String	Rendered content in the given format
	 **/
	public function getContent($format, $query = '')
	{
		return GizLayoutor::make($format)->render($this->path);
	}
		
	/**
	 * mod_rewrite aware i.e. checks the REWRITE_URLS global
	 * 
	 * @param	$dir	String|SplFileInfo
	 * @return 	String	the correct URL for links given a virtual path.
	 * @deprecated Use Path->url() instead.
	 * @see REWRITE_URLS
	 */
	static public function getURL($dir)
	{
		if(is_a($dir, 'FSObject'))
		{
			// Figure out its URL by subtracking the web root
			if($dir->isDir())
				$from = WEB_ROOT . CONTENT_DIR;
			else
				$from = WEB_ROOT;
			
			$dir = Path::open($from)->from($dir->getPathname());
		}
		
		return (REWRITE_URLS) 	// If we're using Apaches mod_rewrite...
			? ((BASE_URL_PATH)	// ... append the BASE_URL_PATH to the $dir...
				? ((BASE_URL_PATH == '/')	// ...only if BASE_URL_PATH is not '/'
					? ''
					: BASE_URL_PATH.'/')
				: '') . "$dir" // force objects __toString()
			: BASE_URL_PATH . "?path=$dir";	// ... ugly URLs then.
	}
	
	/**
	 * @return 	String	Value of the CGI "path" variable i.e. the "virtual path"
	 * @deprecated Use Path->url() instead.
	 */
	public function vpath()
	{
		return $this->getPath()->get();
	}
	
	/**
	 * Builds a list of HTML links to the top level folders in the Content folder
	 * 
	 * @return 	Array	List of HTML anchor tags to the top level folders.
	 */
	function menu()
	{
		$out = array();

		foreach(Path::open($this->contentRoot())->query('folders.has.^[^_]') as $Dir)
		{
			$class = ( $this->currentPath()->url() == $Dir->getURL() )? 'Selected': '';
			
			$out[$Dir->getCleanName()] = $Dir->htmlLink(null, $class);
		}

		return $out;
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
		// remove .. & .
		$dirs = array_diff( scandir( $outerDir ), Array( '.', '..' ) );
		
		$out = Array();
		
		foreach( $dirs as $d )
			if( is_dir($outerDir.'/'.$d) and $depth )
			{
				$out[ $outerDir.'/'.$d ] = FS::getDirectoryTree( $outerDir.'/'.$d, $depth - 1);
			} 
			elseif(!$only_dirs)
			{
				$out[ $outerDir.'/'.$d ] = $d;
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
	 * Add content to the $header variable.
	 * Used to add HTML to the header/footer of the template.
	 * These variables will be made available to the template as $head or $foot etc.
	 * The value is 
	 * 
	 * @param	String		HTML to add to the variable.
	 * @param	String		Name of the variable. Usually 'head' or 'foot' for standard HTML doc header or footer but can be anything.
	 **/
	public static function add($html, $var = 'head')
	{
		$fs = FS::get();
		
/*	This is too hard. Look for a nice way to implement uniqunes in includes...
		
		// Does all this seem a bit silly just to append to a string...?
		if(array_key_exists($fs->content, $var))
		{
			if(!array_key_exists($fs->content[$var], md5($html)))
			{
				// If it does exisit then we already have it.
				$fs->content[$var][md5($html)] = $html;
			}
		}
		else
			$fs->content[$var] = array(md5($html) => $html);
*/
		if(isset($fs->content[$var]))
			$fs->content[$var] .= $html;
		else
			$fs->content[$var] = $html;
	}
}

