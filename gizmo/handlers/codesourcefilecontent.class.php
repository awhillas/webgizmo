<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * GeSHi library
 */
include_once(INCLUDES_PATH.'/geshi/gefunctions_parce.php'); 

/**
 * Handler for source code.
 * 
 * Uses the GeSHi syntax highlighting library
 *
 * @package WebGizmo
 * @subpackage	ContentHanders
 * @link  http://qbnz.com/highlighter/
 **/
class CodeSourceFileContent extends TextFileContent
{	
	protected $_display_filename = 'full';
	
	function html($format = 'html')
	{
		$Geshi = new GeSHi();
		
		$Geshi = $geshi->load_from_file($this->get()->getRealPath());
		
		return $this->renderHTML($format, $Geshi->parse_code());
	}
}