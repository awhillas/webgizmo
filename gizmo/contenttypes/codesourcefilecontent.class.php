<?php
include_once(INCLUDES_PATH.'/geshi/gefunctions_parce.php'); 

class CodeSourceFileContent extends TextFileContent
{	
	protected $_display_filename = 'full';
	
	function html($format = 'xhtml1.1')
	{
		$Geshi = new GeSHi();
		
		$Geshi = $geshi->load_from_file($this->get()->getRealPath());
		
		return $this->renderHTML($format, $Geshi->parse_code());
	}
}