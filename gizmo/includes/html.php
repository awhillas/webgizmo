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
 * @package WebGizmo
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * General purpose HTML tag function.
 * Can be used to generate any HTML markup
 * 
 * @package WebGizmo
 * 
 * @param	String
 * @param	Boolean	Is it a single tag or does it have a closing tag? Note: 
 * 		always renders as a XHTML single tag at the moment.
 * @param	String	If it has a closing tag, then this is the content rendered between the two.
 * @param	String	CSS class(es)
 * @param	String	ID used for CSS and Javascript.
 * @param	Array	Array of other attributes where the key is the attribute and the value is the value.
 * 
 * @return String	A well formed HTML tag
 **/
function tag($tag, $single = true, $content = '', $class = '', $id = '', $attrs = array())
{
	$out = '<'.$tag;
	
	if(!empty($class)) $attrs['class'] = $class;
	
	if(!empty($id)) $attrs['id'] = $id;	
	
	foreach($attrs as $attr => $value) 
		$out .= " $attr=\"$value\"";
	
	return $out . ( $single ? ' />' : '>'.$content.'</'.$tag.'>');
}
/**
 * @return String	A well formed HTML tag
 * @package WebGizmo
 **/
function div($content = '', $class = '', $id = '', $attrs = array())
{
	return tag('div', false, $content, $class, $id, $attrs);
}

/**
 * @return String	A well formed HTML tag
 * @package WebGizmo
 **/
function img($url, $alternate_text = '', $class = '', $id = '', $attrs = array())
{
	$attrs['src'] = $url;
	$attrs['alt'] = $alternate_text;
	
	return tag('img', true, null, $class, $id, $attrs);
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

	foreach($list_items as $li_id => $lang)
	{
		$li_id = (!is_numeric($li_id)) ? strtoupper($li_id): '';
		$content .= li($lang, '', $li_id)."\n";
	}
	
	return tag($list_type, false, $content, $class, $id, $attrs);
}

/**
 * @return String	A well formed HTML tag
 * @package WebGizmo
 **/
function li($content, $class = '', $id = '')
{
	return tag('li', false, $content, $class, $id);
}

/**
 * @return String	A well formed HTML tag
 * @package WebGizmo
 **/
function ul($list_items, $class = '', $id = '', $attrs = array())
{
	return html_list($list_items, 'ul', $class, $id, $attrs);
}

/**
 * Ordered List
 * 
 * @return String	A well formed HTML tag.
 * @package WebGizmo
 **/
function ol($list_items, $class = '', $id = '', $attrs = array())
{
	return html_list($list_items, 'ol', $class, $id, $attrs);
}

/**
 * @return String	A well formed Anchor HTML tag.
 * @package WebGizmo
 **/
function a($href, $text, $class = '', $id = '', $attrs = array())
{
	$attrs['href'] = $href;
	
	return tag('a', false, $text, $class, $id, $attrs);
}

/**
 * @todo finish this.
 */
// function link($href, $type, $rel = null)
// {
// 	$attrs['href'] = $href;
// 	$attrs['type'] = $type;
// 	if(!is_null($ref)) $attrs['rel'] = $rel;
// 	
// 	return tag('link', true, null, null, null, $attrs);
// }

// - - - - - - - - - - - - -
// HTML 5 tags
// - - - - - - - - - - - - -
/**
 * @return String	A well formed HTML 5 Video tag.
 * @package WebGizmo
 **/
function video($source, $class = '', $id = '', $attrs = array(), $not_supported_message = '') {
	
	$attrs['src'] = $source;
	
	$attrs['width'] = 320;
	$attrs['height'] = 240;
	
	return tag('video', false, '', $class, $id, $attrs);
}

/**
 * @return String	A well formed HTML 5 Audio tag.
 * @package WebGizmo
 **/
function audio($source, $class = '', $id = '', $attrs = array(), $not_supported_message = '') {
	
	$attrs['src'] = $source;
	
	return tag('audio', false, '', $class, $id, $attrs);
}

