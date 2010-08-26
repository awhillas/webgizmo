<?php

class Feed
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