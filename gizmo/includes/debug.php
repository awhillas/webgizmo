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
 * @return String
 **/
function pr($subject)
{
	echo "\n".print_r($subject, true)."\n";
}

/**
 * shortcut for var_dump()
 *
 * @return String
 **/
function vd($subject)
{
	var_dump($subject);
}

/**
 * Stack Dump
 *
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
 */
function defvar()
{
	pr(get_defined_vars());
}

/**
 * Dump all the user defined constants and their values.
 */
function defcon()
{
	$cons = get_defined_constants(true);
	pr($cons['user']);
}