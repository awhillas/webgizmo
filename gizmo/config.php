<?php
/**
 * Global variable configuration constants
 * 
 * All global variables here can be overridden in the index.php file 
 * so can be shared by multiple websites.
 *
 * Naming conventions for the various PATH/DIRs, names ending in:
 *  		_PATH = Server absolute paths
 *  		_DIR = Name of single directory (not a full path)
 * 			_URL = The URL of the resource (direct no virtual)
 * 
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * @global	String	Version number of this install of Web Gizmo
 */
if (!defined('GIZMO_VERSION')) define('GIZMO_VERSION', '0.3 beta');
/**
 * @global	Boolean	Show debugging info, like errors.
 */
if (!defined('DEBUG')) define('DEBUG', false);


// // // // // // // // // // // // // // // // //
// Path constants
// // // // // // // // // // // // // // // // //

/**
 * @global	string	Server side absolute path to the "web root", where the index.php file is and the request is being served from.
 */
if (!defined('WEB_ROOT')) define('WEB_ROOT', realpath(dirname($_SERVER['SCRIPT_FILENAME'])));
/**
 * @global	string	Gizmo folder name.
 */
if (!defined('GIZMO_DIR')) define('GIZMO_DIR', '/gizmo');
/**
 * @global	string	Server path to the instillation of Gizmo i.e. where this file is.
 */
//if (!defined('GIZMO_PATH')) define('GIZMO_PATH', dirname(__FILE__));
if (!defined('GIZMO_PATH')) define('GIZMO_PATH',  WEB_ROOT.GIZMO_DIR);
/**
 * @global	string	
 */
if (!defined('INCLUDES_PATH')) define('INCLUDES_PATH', GIZMO_PATH.'/includes');
/**
 * Assumed to be within the WEB_ROOT.
 * @global	string	
 */
if (!defined('CONTENT_DIR')) 	define('CONTENT_DIR', '/content');
/**
 * @global	string	
 */
if (!defined('TEMPLATES_DIR')) define('TEMPLATES_DIR', '/templates');
/**
 * @global	string	
 */
if (!defined('PLUGINS_DIR')) define('PLUGINS_DIR', '/plugins');
/**
 * @global	string	Gizmo plugins path. These should always be loaded automatically.
 */
if (!defined('PLUGINS_PATH')) define('PLUGINS_PATH', GIZMO_PATH.PLUGINS_DIR);
/**
 * @global	string	Prefix of all plugin generated paths. Plugins will be passed control
 * 		by Gizmo by using the following naming convention in the virtual path: GIZMO_PLUGIN_URL_PREFIX.'/'.<plugin class>
 */
if (!defined('GIZMO_PLUGIN_URL_PREFIX')) define('GIZMO_PLUGIN_URL_PREFIX', '/gizplugin');

// URLs

/**
 * If gizmo is not in the root then add this to base URL i.e. beginning of all link URLs
 * e.g. of you had gizmo in 'www.example.com/something' then you would set this to '/something'
 * @see FS::getURL()
 * @global	String
 */
if (!defined('BASE_URL_PATH')) define('BASE_URL_PATH', '');
/**
 * @global	string	Plugins base URK
 */
if (!defined('PLUGINS_URL')) define('PLUGINS_URL', BASE_URL_PATH.GIZMO_DIR.PLUGINS_DIR);

// // // // // // // // // // // // // // // // //
// Behaviour Settings
// // // // // // // // // // // // // // // // //

/**
 * @global	string	Sub-path of the content folder to redirect to if you don't want the root folder to be it.
 */
if (!defined('DEFAULT_START')) define('DEFAULT_START', '/');

if (!defined('REWRITE_URLS'))
{
	// Try to detect if mod_rewrite is installed and the .htaccess is setup correctly.
	if (isset($_SERVER['GIZ_HTTP_MOD_REWRITE']) AND $_SERVER['GIZ_HTTP_MOD_REWRITE'] == 'On')
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
 * If true then you Gizmo assumes that the top level folders in /content are
 * ISO 639-1 codes and content root is taken to start under each of these.
 * @see http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes 
 * @global	Boolean		Is the Content folder divided into languages at the root level?
 */
if (!defined('MULTI_LINGUAL')) define('MULTI_LINGUAL', false);
/**
 * @global	string		Which language should be the default language if the browser 
 * 						sniffed one isn't present? Should be an ISO 639-1 codes.
 */
if (!defined('DEFAULT_LANGUAGE')) define('DEFAULT_LANGUAGE', 'en');
/**
 * @global	String	Character set used, used for htmlentities() function and HTML document header
 * @link http://php.net/manual/en/function.htmlentities.php
 * @see FS::strip()
 **/
if (!defined('CHAR_ENCODING')) define('CHAR_ENCODING', 'utf-8');


// // // // // // // // // // // // // // // // //
// Content Variables...
// // // // // // // // // // // // // // // // //

/**
 * @global	string	Website title/name
 */
if (!defined('SITE_TITLE')) define('SITE_TITLE', 	$_SERVER['SERVER_NAME']);
/**
 * @global	string	Website description. Shouldn't be longer than one sentence.
 */
if (!defined('SITE_DESCRIPTION')) define('SITE_DESCRIPTION', '');
/**
 * @global	string	
 */
if (!defined('THEME_DIR')) define('THEME_DIR', '/default');
/**
 * @global	string		How subfolders are handled. Can be 'links', 'teaser' or 'none'	
 */
if (!defined('FOLDER_DISPLAY')) define('FOLDER_DISPLAY', 'none');
/**
 * @global	string		Overrider of the default HTML layout render'er (Layoutor)	
 */
if (!defined('HTML_LAYOUT')) define('HTML_LAYOUT', 'FormatHTML');
/**
 * @global	integer		Default HTML version
 */
if (!defined('HTML_DEFAULT_VERSION')) define('HTML_DEFAULT_VERSION', 4);