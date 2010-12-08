<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Basic Render
 * 
 * Basic rendered. Example of a render class. Inherit and override ...
 *
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @todo Delete this class and replace to call to it in FS with GizLayoutor::make()
 **/
class BasicRender
{
	private $_path;
	
	/**
	 * @param	$content	Path object
	 */
	public function __construct($content)
	{
		$this->_path = $content;
	}
	
	public function render($args = array(), $format = 'html')
	{
		$out = '';
		
		// Get the content and render each
		foreach($this->_path as $file) 
			$out .= $file->render($format);
		
		return $out;
	}
	
} // END class 