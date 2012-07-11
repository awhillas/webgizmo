<?php
/**
 * Error handeling setup
 * 
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

define('KRUMO_TRUNCATE_LENGTH', 255);
/**
 * Nice data dump output.
 */
include 'krumo/class.krumo.php';

$PAGE_ERRORS = '';

/**
 * Should errors be saved till the end of all output or be inline?
 */
if(!defined('ERRORS_AT_END')) define('ERRORS_AT_END', true);

if(!defined('ERROR_LEVEL'))	define('ERROR_LEVEL', E_ALL | E_STRICT);


// Set the error reporting to use our custom funciton (which uses krumo!).
// if debugging is on.
if(DEBUG)
	set_error_handler('errorHandler', ERROR_LEVEL);
else
	error_reporting(0);

/**
 * Handle errors
 * @todo Save errors and dump them after the page has loaded, not in the page.
 */
function errorHandler( $errno, $errstr, $errfile, $errline, $errcontext)
{
	global $PAGE_ERRORS;
	
	if ( 0 == error_reporting () ) {
		// Error reporting is currently turned off or suppressed with @
		return;
	}
	
	if(ERRORS_AT_END)
	{
		ob_start(); // Start to capture errors...

		// if(empty($PAGE_ERRORS))
		// {
		// 	echo '<div class="protection div"></div>';
		// 	krumo($GLOBALS);
		// }
	}	
	
//	echo 'Into '.__FUNCTION__.'() at line '.__LINE__.
	// pr( $errno, true).
	pr( $errstr).
	pr( $errfile . "(Line: $errline)");
	// pr( $errcontext, true);

	$stack = debug_backtrace();
	array_shift($stack);	// remove the call to this function
	
	// Capture errors for display after.
	//pr($stack);
	krumo($stack);

	if(ERRORS_AT_END)
		$PAGE_ERRORS .= ob_get_clean();	// ... finish capturing errors.
	
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