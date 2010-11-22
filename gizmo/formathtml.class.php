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
			$tpl->here = &$fs->currentPath();
			$tpl->templates = $fs->templatePath($this->format)->realURL();
			$tpl->title = htmlentities(SITE_TITLE);
			$tpl->langauge = $fs->getLanguage();
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
				// Instanciate a handler for the file and render
				$tag = $FSObject->getTag();
				$tag = (empty($tag))? 'content': $tag;
				
				if(isset($out[$tag]))
					$out[$tag] .= $FSObject->render();
				else
					$out[$tag] = $FSObject->render();
			}
		}
		else
			trgger_error('Can not render path: '.$Path);
		
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
				case 'text/javascript':
					$out .= '<script src="'.$file.'" type="'.$mime.'"></script>'."\n";
					break;
					
				case 'text/css':
					$rel = 'stylesheet';
					
				default:
					$out .= link($file, $mime, $rel)."\n";
			}
		}
			
		return $out;
	}
	
} // END class FormatHTML