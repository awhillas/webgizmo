<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * RSS Feed reader class
 *
 * @package WebGizmo
 * @subpackage	GizPlugins
 * 
 * @todo make the Feed class work using SimplePie
 **/
class Feed extends GizPlugin
{
	private $_feed;
	
	function __constrcut($url)
	{		
		// Get the Feed via cURL
		$ch = curl_init(); 								// create curl resource 
		curl_setopt($ch, CURLOPT_URL, $url); 			// set url 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 	//return the transfer as a string
		$rss = curl_exec($ch); 	// $rss contains the output string 
		curl_close($ch);		// close curl resource to free up system resources 

		// Pasre with SimpleXML
		$this->_feed = new SimpleXMLElement($xmlstr);
	}

	function first()
	{
		//return $this->_feed->item[0];
	}
}