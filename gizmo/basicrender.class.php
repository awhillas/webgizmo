<?php
/**
 * Basic Render
 * 
 * Basic rendered. Example of a render class. Inherit and override ...
 *
 * @package WebGizmo
 * @author Alexander R B Whillas
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