<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * @global	string		Overrider of the default HTML layout render'er (Layoutor)	
 */
if (!defined('HTML_LAYOUT'))	define('HTML_LAYOUT',	'FormatHTML');

/**
 * @global	integer		Default HTML version
 */
if (!defined('HTML_DEFAULT_VERSION'))	define('HTML_DEFAULT_VERSION',	4);

/**
 * @global	String		URL to the latest version of JQuery. 
 * 						Useful for plugins but not sure where I should put this :-/ 
 */
if (!defined('JQUERY_URL'))			define('JQUERY_URL',	'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
if (!defined('JQUERY_URL'))			define('JQUERY_URL',	'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');

/**
 * @global	String		URL to the latest version of the Dojo Toolkit. 
 */
if (!defined('DOJOTOOLKIT_URL'))	define('DOJOTOOLKIT_URL',	'http://ajax.googleapis.com/ajax/libs/dojo/1.4/dojo/dojo.xd.js');



/**
 * Basic HTML render'er 
 * 
 * Not too specific to any particular version of HTML.
 *
 * @package WebGizmo
 * @subpackage	Layoutors
 **/
class FormatHTML extends GizFormat
{
	/**
	 * Constructor
	 *
	 * @return FormatHTML
	 **/
	public function __construct($format = 'html', $version = 4)
	{
		parent::__construct($format, $version);
		
		$this->format = 'html';
	}
	
	/**
	 * Render the content as HTML
	 *
	 * @return String
	 **/
	public function render()
	{
		// Get the template for the format
		if($tpl = $this->getTemplateEngine())
		{
			$fs = FS::get();
			
			// Standard Gizmo stuff...
			$tpl->format = $this->format;
			$tpl->formatVersion = $this->version;
			
			$tpl->fs = &$fs;
			$tpl->here = $fs->currentPath();
			$tpl->home = BASE_URL_PATH.DEFAULT_START;
			$tpl->templates = $fs->templatePath($this->format)->realURL();
			$tpl->title = SITE_TITLE;
			$tpl->pagetitle = $fs->currentPath()->getObject()->getCleanName();
			$tpl->language = $fs->getLanguage();
			$tpl->gizmo_version = GIZMO_VERSION;
	
			// Assign the rendered content to the template
			$tpl->assign($this->renderContent());

			// Add the header links to CSS & Javascript etc...
			// Must happen _after_ renderContent() as plugins use the FS::addRef() method.
			$fs->add($this->renderFileReferences(), 'head', true);			

			// Shared global vars used by plugins and handlers.
			$tpl->assign($fs->content);

			// Return the content rendered in the correct template.
			// Template is determined in $this->getTemplateEngine();
			return $tpl->getOutput();
		}
	}
	
	/**
	 * Instantiate the Template Engine and find all the appropriate templates 
	 * for the format.
	 * 
	 * Override this to use a different template engine i.e. Smarty or some such.
	 * 
	 * @link http://phpsavant.com/
	 * @link http://devzone.zend.com/article/9075
	 */
	private function getTemplateEngine()
	{
		$Path = FS::get()->templatePath();
		
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
			$tpl = new Savant3($options);
			
			// Set the template we will use to render the content.
			$tpl->setTemplate($this->getTemplate());
			
			return $tpl;
		}
		else
		{
			trigger_error('Template path could not be found: '.$Path, E_USER_ERROR);
			return false;
		}
	}
	
	/**
	 * Figure out which template file we should be using for the current content.
	 * 
	 * Override this to have different template picking logic for Savant
	 *
	 * @return String
	 **/
	private function getTemplate()
	{
		$fs = FS::get();
		
		// Where the templates live
		$TemplatesPath = $fs->templatePath();
		
		// Get the current path as a Virtual path
		$all_parts = explode('/', FS::realToVirtual($fs->currentPath()->less($fs->contentRoot())));
//		array_shift($all_parts); // Remove the empty at the begining

		// Special front.tpl.php (1st page intro)
		// TODO: Check if DEFAULT_START is defined 
		if(
			count($all_parts) == 1 AND empty($all_parts[0]) 		// we're at '/'
			AND file_exists($TemplatesPath . '/' . 'front.tpl.php')	// and the front.tpl.php is present
		)
			return 'front.tpl.php';


		if(count($all_parts))
		{
			// Look for specific templates
			$parts = $all_parts;
			while(count($parts) and $parts[0] != '')
			{
				$candidate = implode('_', $parts).'.tpl.php';

				if(file_exists($TemplatesPath . '/' . $candidate))
				{
					return $candidate;
				}
				else
				{
					array_shift($parts);
				}
			}
			
			// Look for inherited/general templates
			$parts = $all_parts;
			array_pop($parts);	// Should not affect the current folder, only children
			foreach(array_reverse($parts) as $folder)
			{
				$candidate = $folder.'_default.tpl.php';

				if(file_exists($TemplatesPath.'/'.$candidate))
					return $candidate;
			}
		}
				
		return 'index.tpl.php';
	}
	
	/**
	 * Render the content as HTML and group by _tag_
	 *
	 * @return Array	Where keys are 'tags' + 'content' (always present).
	 **/
	public function renderContent()
	{
		$out['content'] = "\n\n<!-- FormatHTML::render() start ... -->\n\n";
		
		if($Dir = FSObject::make(FS::get()->currentPath()) AND $Dir->isDir())
		{
			foreach($Dir->getContents() as $FSObject)
			{
				// Instantiate a handler for the file and render
				$tag = $FSObject->getTag();
				$tag = (empty($tag))? 'content': $tag;
			
				if(isset($out[$tag]))
					$out[$tag] .= $FSObject->render();
				else
					$out[$tag] = $FSObject->render();
			}
		}
		else
			trigger_error('Can not render path: '.$Dir);
		
		$out['content'] .= "\n\n<!-- ... FormatHTML::render() end. -->\n\n";
		
		return $out;
	}

	/**
	 * Parses the $FS::fileReferences[] array and creates HTML code linking to each.
	 *
	 * @return String	HTML
	 * @todo Group CSS and JS together perhaps...?
	 **/
	private function renderFileReferences()
	{
		$out = '';
		
		foreach(FS::get()->fileRefs() as $file => $mime)
		{
			$rel = null;
			
			switch($mime)
			{
				// case 'text/javascript':
				// 	//$out .= '<script src="'.$file.'" type="'.$mime.'"></script>'."\n";
				// 	$out .= rel($file, $mime);
				// 	break;
					
				case 'text/css':
					$rel = 'stylesheet';
					
				default:
					$out .= "\t".rel($file, $mime, $rel)."\n";
			}
		}
			
		return $out;
	}
	
} // END class FormatHTML