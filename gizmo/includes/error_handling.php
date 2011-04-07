<?php
/**
 * Error handeling setup
 * 
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Nice data dump output.
 */
include 'krumo/class.krumo.php';

$PAGE_ERRORS = '';

if(!defined('ERROR_LEVEL'))	
	define('ERROR_LEVEL', E_ALL | E_STRICT);


// Set the error reporting to use krumo!
//error_reporting(0);
set_error_handler('errorHandler', ERROR_LEVEL);

/**
 * Handle errors
 * @todo Save errors and dump them after the page has loaded, not in the page.
 */
function errorHandler( $errno, $errstr, $errfile, $errline, $errcontext)
{
	if ( 0 == error_reporting () ) {
		// Error reporting is currently turned off or suppressed with @
		return;
	}

//	echo 'Into '.__FUNCTION__.'() at line '.__LINE__.
	// pr( $errno, true).
	pr( $errstr).
	pr( $errfile . "(Line: $errline)");
	// pr( $errcontext, true);

	$stack = debug_backtrace();
	array_shift($stack);	// remove the call to this function
	krumo($stack);
	// krumo::backtrace();
}

function checkStackDump(&$data)
{
	if(is_array($data))
	{
		foreach($data as $key => $value)
			switch(true)
			{
				case is_object($value):
					$value = (array) $value;
				
				case is_array($value):
					$data[$key] = checkStackDump($value);
					break;
				
				default:
					$data[$key] = htmlentities((string)$value);
			}
	}
	return $data;
}