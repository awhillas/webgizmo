<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * handelr for text files
 * 
 * Will try to handle whitespace nicely.
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 * @author Alexander R B Whillas
 **/
class TextFileContent extends FSFile
{	
	/**
	 * Raw contents of the Text file read in by the constructor
	 * @var	String
	 */
	protected $_content;
	
	/**
	 * Should the default HTML rendering display the full filename or a cleaned version?
	 * P{ossible options are: 
	 * 		'clean' - interpreted file name, 
	 * 		'full' - full filename as it appears in the OS, 
	 * 		'none' - do not display the file name.
	 * @var	String
	 * @see TextFileContent::renderHTML
	 */
	protected $_display_filename = 'none';
	
	function __construct($file)
	{
		parent::__construct($file);
				
		$this->_content = $this->getContents(); //file_get_contents($this->get()->getRealPath(), FILE_TEXT);		
	}
	
	/**
	 * @todo Should try to parse the white space and wrap in DIV
	 */
	function html($format = 'html')
	{
		return $this->renderHTML($format = 'html', "<pre>$this->_content</pre>");
	}
	
	/**
	 * Function to do the standard text file handling behavior so that descendants can optionally call it 
	 */
	protected function renderHTML($format = 'html', $content)
	{
		$class_name = 'Text '.get_class($this);

		$title = null;
		
		if($this->_display_filename != 'none')
		{
			switch($this->_display_filename)
			{
				case 'clean':
					$title = $this->getCleanName();
					break;
			
				case 'full':
					$title = $this->getFilename();
					break;
			}
				
			return "
			<div class=\"$class_name\" id=\"".$this->getID()."\">
				<h2 class=\"FileName\">$title</h2>
				<div class=\"Content\">$content</div>
			</div>
			";
		}
		else
		{
			return "
			<div class=\"$class_name\" id=\"".$this->getID()."\">
				<div class=\"Content\">$content</div>
			</div>
			";			
		}
	}
	
	function __toString()
	{
		return $this->_content;
	}
	
	function append($string)
	{
		$this->_content = $this->_content.$string;
		return $this;
	}
	
	/**
	 * Truncates the internal content.
	 * Call before calling a render function i.e. $text->truncate(100)->html();
	 *
	 * @return TextFileContent object Its self so can be chained
	 * @author Chirp Internet: www.chirp.com.au
	 * @see http://www.the-art-of-web.com/php/truncate/
	 **/
	function truncate($limit, $break = ".", $pad = "...")
	{
		// return with no change if string is shorter than $limit
		if(!(strlen($this->_content) <= $limit))
		{
			$clone = clone $this;
			
			// is $break present between $limit and the end of the string?
			if(false !== ($breakpoint = strpos($clone->_content, $break, $limit) + 1)) 
			{
				if($breakpoint < strlen($clone->_content) - 1) 
				{
					$clone->_content = substr($clone->_content, 0, $breakpoint) . $pad;
				}
			}
			return $clone;
		}
		else
			return $this;
	}
}
