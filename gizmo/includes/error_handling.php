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
	$out = '';

	if ( 0 == error_reporting() ) {
		// Error reporting is currently turned off or suppressed with @
		return;
	}
	if(ERRORS_AT_END)
		ob_start(); // Start to capture errors...
	
	pr( $errstr).
	pr( $errfile . " (Line: <strong>$errline</strong>)");

	// Pretty output
	$stack = debug_backtrace();
	array_shift($stack);	// remove the call to this function
	krumo($stack);

	if(ERRORS_AT_END)
		$out .= ob_get_clean();	// ... finish capturing errors.
	
	echo $out;
}