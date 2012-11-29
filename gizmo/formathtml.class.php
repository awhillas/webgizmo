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
//if (!defined('JQUERY_URL'))	define('JQUERY_URL', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
if (!defined('JQUERY_URL'))	define('JQUERY_URL', 'http://code.jquery.com/jquery-latest.min.js');

/**
 * @global	String		URL to the latest version of the Dojo Toolkit. 
 */
if (!defined('DOJOTOOLKIT_URL'))	define('DOJOTOOLKIT_URL', 'http://ajax.googleapis.com/ajax/libs/dojo/1.4/dojo/dojo.xd.js');



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
			$tpl->description = SITE_DESCRIPTION;
			$tpl->language = $fs->getLanguage();
			$tpl->gizmo_version = GIZMO_VERSION;

			if($fs->currentPath()->get() != $fs->contentRoot()->get()) 
				$tpl->pagetitle = $fs->currentPath()->getObject()->getCleanName();
			else
				$tpl->pagetitle = SITE_TITLE;
	
			// Get rendered content grouped by _tag_
			$tpl->assign($this->renderContent());

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
	 * Figure out which template file we should be using for the current content path.
	 * 
	 * This is also used for CSS/LESS file inclusion. This keeps the logic centralised.
	 * 
	 * Override this to have different template picking logic for Savant
	 * 
	 * @param	String	The file extension to add to the file-basename when generating file name patterns
	 * @param	String	The default basename to use if nothing matches. $extension will be added to this.
	 *
	 * @return String
	 **/
	private function getTemplate($extension = '.tpl.php', $default_basename = 'index')
	{
		$fs = FS::get();
		
		// Where the templates live
		$TemplatesPath = $fs->templatePath();

		// Get the current content path as a Virtual path
		// which we will use to find matching templates
		$VPath = FS::realToVirtual($fs->currentPath()->less($fs->contentRoot()));

		// Special front.tpl.php (1st page intro)
		// TODO: Check if DEFAULT_START is defined 
		if(
			$VPath->get() == '' 		// we are at '/'
			AND $TemplatesPath->add('front'.$extension)->is()	// and the front.tpl.php is present
		)
			return 'front.tpl.php';

		if(count($parts = $VPath->parts()))
		{
			// Look for specific templates
			while(count($parts))
			{
				$candidate = implode('_', $parts).$extension;

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
			
			array_pop($parts);	// Should not affect the current folder, only children

			foreach(array_reverse($VPath->parts()) as $folder)
			{
				$candidate = $folder.'_default'.$extension;

				if(file_exists($TemplatesPath.'/'.$candidate))
					return $candidate;
			}
		}
		
		// Fall back to the default 
		
		$candidate = $default_basename.$extension;
		
		if(file_exists($TemplatesPath.'/'.$candidate))		
			return $candidate;
		else
		{
			trigger_error('Template could not be found: '.$TemplatesPath.'/'.$candidate, E_USER_ERROR);
			die;
		}
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
				$tag = (empty($tag))? 'content': $tag;	// Default tag is "content"
				
				if($tag != 'content' || strpos($FSObject->getBasename(), '_') !== 0) 
				{
					if(isset($out[$tag]))
						$out[$tag] .= $FSObject->render();
					else
						$out[$tag] = $FSObject->render();
				}
			}
		}
		else
			trigger_error('Can not render folder: '.$Dir);
		
		$out['content'] .= "\n\n<!-- ... FormatHTML::render() end. -->\n\n";

		$fs = FS::get();
		// Add the header links to CSS & Javascript etc...
		// Must happen _after_ reother content is rendfered as plugins use the FS::addRef() method.
		$fs->add($this->renderFileReferences(FS::get()->fileRefs()), 'head', true);	// prepend
		
		// Add auto file picks like fonts and CSS etc...
		$refs = $this->getTemplateAutoIncludes($fs->templatePath($this->format));
		$fs->add($this->renderFileReferences($refs));

		foreach($fs->content as $tag => $html)
			if(array_key_exists($tag, $out))
				$out[$tag] .= $html;
			else
				$out[$tag] = $html;

		return $out;
	}

	/**
	 * Parses the $FS::fileReferences[] array and creates HTML code linking to each.
	 * @param	Array	List of file references in the format: array('Path to file reference' => 'mime/type')
	 * @return 	String	HTML
	 * @todo Group CSS and JS together perhaps...?
	 * @todo If mime part is empty then guess it...
	 **/
	private function renderFileReferences($file_ref_list)
	{
		$out = '';
		
		foreach($file_ref_list as $file => $mime)
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
	
	/**
	 * Pick up files to reference from the template folder
	 * 
	 * This is where auto includes happen including fonts, js, css, background images etc
	 *
	 * @return Array	List of file URLs to include in the <head> of the page.
	 * @todo 	This is clumsy and should be using the main stream Extension -> Handler mapper but specialised for the /templates folder/subfolders.
	 **/
	private function getTemplateAutoIncludes(Path $TemplatePath)
	{
		$fs = FS::get();
		$out = array();
		$includeLess = false;
		
		foreach(array('fonts', 'css', 'js') as $aspect)
		{
			$AspectPath = $TemplatePath->add($aspect);

			if($AspectPath->is())	// path exists
			{
				$filter = $aspect;
				$mime = 'text/javascript';
				
				switch($aspect)
				{
					case 'fonts':
						// Fonts are a special case for fonts which are in their own folders= with 
						// with a CSS file to handle the include.
						foreach($AspectPath->query('folders.contents')->name('(?i)(css)$') as $CssFile)
						{
							$out[$CssFile->getPath()->realURL()] = 'text/css';
						}
						break;
						
					case 'css':
						// Add support for LESS files
						$filter = 'css|less';
						$mime = 'text/css';
						
					default:
						// CSS and Javascript are handled the same way.
						
						foreach($AspectPath->files()->name('(?i)('.$filter.')$') as $CssFile)
						{
							$url = $CssFile->getPath()->realURL();
							
							if($CssFile->getExt() == 'less') 
								$out[LESS::htmlLinkUrl($CssFile->getPath()->realURL())] = $mime;
							else
								$out[$url] = $mime;
						}
						break;
				}
			}
		}
		return $out;
	}
	
} // END class FormatHTML