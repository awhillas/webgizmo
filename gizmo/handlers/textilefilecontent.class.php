<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * The Textile parser class
 */
include_once(INCLUDES_PATH.'/classTextile.php');

/**
 * Textile: A Humane Web Text Generator
 * 
 * Textile takes plain text with *simple* markup and produces valid XHTML. 
 * It's used in web applications, content management systems, blogging software 
 * and online forums.
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 * @author Alexander R B Whillas
 * @see http://textile.thresholdstate.com/
 **/
class TextileFileContent extends TextFileContent
{	
	function html($format = 'html')
	{
		$textile = new Textile();
		
		return $this->renderHTML($format, $textile->TextileThis($this->_content));
	}
}
