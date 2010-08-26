<?php
//include_once(INCLUDES_PATH.'/rtf2html/functions_parce.php');
include_once(INCLUDES_PATH.'/rtfclass.php');

class RTFFileContent extends TextFileContent
{	
	protected $_rtf;

	function __construct($file)
	{
		parent::__construct($file);
		
		$this->_rtf = new rtf( $this->_content );
	}

	function html($format = 'xhtml1.1')
	{
		return $this->convert('html');
	}

	function xml($format = 'xml1.0')
	{
		return $this->convert('xml');
	}
	
	function convert($format = 'html')
	{		
		if(in_array($format, array('html', 'xml')))
		{
			$this->_rtf->output($format);
			$this->_rtf->parse();
			if( count( $this->_rtf->err) == 0) // no errors detected
				return $this->_rtf->out;
			else
				trigger_error($this->_rtf->err);
		}
		else
		{
			trigger_error('Unknown format to covert RTF to: '.$format);
			return null;
		}
	}
}