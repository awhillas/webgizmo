<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Basic HTML render'er 
 * 
 * Not too specific to any particular version of HTML.
 *
 * @package WebGizmo
 * @subpackage	Layoutors
 **/
class LayoutHTML extends GizLayoutor
{
	/**
	 * Render the content as HTML
	 *
	 * @return String
	 **/
	public function render($format = 'html')
	{
		$out['content'] = "\n\n<!-- LayoutHTML::render() start ... -->\n\n";
		
		if($Dir = FSObject::make(FS::get()->currentPath()) AND $Dir->isDir())
		{		
			foreach($Dir->getContents() as $FSObject)
			{
				// Instanciate a handler for the file and render
				$tag = $tag = $FSObject->getTag();
				$tag = (empty($tag))? 'content': $tag;
				
				$out[$tag] .= $FSObject->render($format);
			}
		}
		else
			trgger_error('Can not render path: '.$Path);
		
		$out['content'] .= "\n\n<!-- ... LayoutHTML::render() end. -->\n\n";
		
		return $out;
	}
} // END class LayoutHTML