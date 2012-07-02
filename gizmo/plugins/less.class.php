<?php
/**
* less.php plugin for server side processing of .less files
* 
* @link https://github.com/dresende/less.php
*/
class LESS
{	
	function __construct()
	{
		# code...
	}
	
	public static function htmlLinkUrl($less_file_url)
	{
		$p = Path::open(INCLUDES_PATH.'/less.php/less.php');
		
		return $p->realURL().'?file='.$less_file_url;
	}
}
