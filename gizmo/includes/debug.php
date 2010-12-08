<?php
/**
 * Handy Debugging functions
 *
 * @package WebGizmo
 * @author Alexander Whillas
 **/

/**
 * shortcut for print_r
 *
 * @package WebGizmo
 * @return String
 **/
function pr($subject)
{
	echo "\n<pre>".print_r($subject, true)."</pre>\n";
}

/**
 * shortcut for var_dump()
 *
 * @package WebGizmo
 * @return String
 **/
function vd($subject)
{
	var_dump($subject);
}

/**
 * Stack Dump
 *
 * @package WebGizmo
 * @return String
 **/
function sd($html = true)
{
	if(!$html)
		pr(debug_backtrace());
	else
		krumo(debug_backtrace());
}

/**
 * Print what type of subject is passed
 *
 * @package WebGizmo
 * @return String
 **/
function what($subject)
{
	switch(true)
	{
		case is_array($subject):
			echo 'Array';
		
		case is_object($subject):
			echo get_class($subject);
	}
}

/**
 * Print all the defined variables in the current scope.
 * @package WebGizmo
 */
function defvar()
{
	pr(get_defined_vars());
}

/**
 * Dump all the user defined constants and their values.
 * @package WebGizmo
 */
function defcon()
{
	$cons = get_defined_constants(true);
	pr($cons['user']);
}