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
 * @author Alexander Whillas
 **/
class HTMLLayoutor extends GizLayoutor
{
	/**
	 * Render the content as HTML
	 *
	 * @param	$Path	Path	Path to the content to render.
	 * @param	$query	String	Query chain to apply to the Paths content.
	 * 
	 * @return String
	 **/
	public function render($Path, $query = '')
	{
print_r($Path);	
		$out = "\n\n<!-- HTMLLayoutor::render() start ... -->\n\n";
		
		if($Dir = FSObject::make($Path->get()) AND $Dir->isDir())
		{
			$content = $Dir->getContents();
			
			if(!empty($query))
				$content = $content->run($query);
				
			foreach($content as $FSObject)
			{
				$out .= $FSObject->render('html');	// We can hardcode the format for now (as this IS the HTMLLayoutor)
			}
		}
		else
			trgger_error('Can not render path: '.$Path);
		
		$out .= "\n\n<!-- ... HTMLLayoutor::render() end. -->\n\n";
			
		return $out;
	}
} // END class HTMLLayoutor