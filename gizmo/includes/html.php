<?php
/**
 * HTML rendering functions
 * 
 * Collection of functions for making HTML less tedious. The convention is
 * That the function name will be the same as the HTML tag name is 
 * its easy to remember/guess. Functions beginning with 'html_' are
 * utility functions used by the rest
 * 
 * Usually: tag($content, $class, $id, $other_attributes)
 *
 * @author Alexander R B Whillas
 * @version $Id$
 * @copyright Taylor Square Designs P/L, 19 April, 2010
 * @package Gizmo
 **/

/**
 * General purpose HTML tag function.
 * Can be used to generate any HTML markup
 *
 * @return void
 * @author Alexander R B Whillas
 **/
function tag($tag, $single = true, $content = '', $class = '', $id = '', $attrs = array())
{
	$out = '<'.$tag;
	
	if(!empty($class)) $attrs['class'] = $class;
	
	if(!empty($id)) $attrs['id'] = strtoupper($id);	
	
	foreach($attrs as $attr => $value) 
		$out .= " $attr=\"$value\"";
	
	return $out . ( $single ? '/>' : '>'.$content.'</'.$tag.'>');
}

/**
 * List
 *
 * @return String	HTML List
 * @author Alexander R B Whillas
 * @todo make the LI's with the tag() function
 **/
function html_list($list_items, $list_type = 'ul', $class = '', $id = '', $attrs = array())
{
	$content = '';

	foreach($list_items as $item_class => $lang)
	{
		$li_class = (!is_numeric($item_class)) ? $item_class: '';
		$content .= li($lang, $li_class)."\n";
	}
	
	return tag($list_type, false, $content, $class, $id, $attrs);
}

function li($content, $class = '', $id = '')
{
	return tag('li', false, $content, $class, $id);
}

/**
 * Unordered List <ul>
 */
function ul($list_items, $class = '', $id = '', $attrs = array())
{
	return html_list($list_items, 'ul', $class, $id, $attrs);
}

/**
 * Ordered List <ol>
 */
function ol($list_items, $class = '', $id = '', $attrs = array())
{
	return html_list($list_items, 'ol', $class, $id, $attrs);
}

/**
 * undocumented function
 *
 * @return void
 * @author Alexander R B Whillas
 **/
function a($href, $text, $class = '', $id = '', $attrs = array())
{
	$attrs['href'] = $href;
	
	return tag('a', false, $text, $class, $id, $attrs);
}

// - - - - - - - - - - - - -
// HTML 5 tags
// - - - - - - - - - - - - -
function video($source, $class = '', $id = '', $attrs = array(), $not_supported_message = '') {
	
	$attrs['src'] = $source;
	
	$attrs['width'] = 320;
	$attrs['height'] = 240;
	
	return tag('video', false, '', $class, $id, $attrs);
}

function audio($source, $class = '', $id = '', $attrs = array(), $not_supported_message = '') {
	
	$attrs['src'] = $source;
	
	return tag('audio', false, '', $class, $id, $attrs);
}