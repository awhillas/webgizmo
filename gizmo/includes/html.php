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
 * @todo Might consider changing all the HTML functions to first letter with a capital to avoid name collisions with PHP.
 **/
function tag($tag, $single = true, $content = '', $class = '', $id = '', $attrs = array())
{
	$out = '<'.$tag;
	
	if(!empty($class)) $attrs['class'] = $class;
	
	if(!empty($id)) $attrs['id'] = $id;	
	
	if(array_key_exists('title', $attrs)) $attrs['title'] = htmlentities($attrs['title']);
	
	foreach($attrs as $attr => $value) 
		$out .= " $attr=\"$value\"";
	
	return $out . ( $single ? ' />' : '>'.$content.'</'.$tag.'>');
}

/**
 * HTML comment
 *
 * @return String
 * @todo Make this also do IE conditional comments
 * @package WebGizmo
 **/
function comment($comment)
{
	return '<!-- '.$comment.' -->';
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
function p($content = '', $class = '', $id = '', $attrs = array())
{
	return tag('p', false, $content, $class, $id, $attrs);
}

/**
 * @return String	A well formed HTML tag
 * @package WebGizmo
 **/
function h($content = '', $level = 1, $class = '', $id = '', $attrs = array())
{
	if(!is_numeric($level) OR $level > 6 OR $level < 0)
		$level = 1;
	
	return tag('h'.$level, false, $content, $class, $id, $attrs);
}


/**
 * @return String	A well formed HTML tag
 * @package WebGizmo
 **/
function img($url, $alternate_text = '', $class = '', $id = '', $attrs = array())
{
	$attrs['src'] = $url;
	$attrs['alt'] = htmlentities($alternate_text);
	
	return tag('img', true, null, $class, $id, $attrs);
}

/**
 * List
 *
 * @return String	HTML List
 * @author Alexander R B Whillas
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
function a($href, $text = '', $class = '', $id = '', $attrs = array())
{
	$attrs['href'] = $href;
	$text = (empty($text))? $href: $text;
	
	return tag('a', false, $text, $class, $id, $attrs);
}

/**
 * Document relationship (A HTML Link)
 * 
 * Since link() is already defined by PHP have to use a different name.
 * 
 * "Although LINK has no content, it conveys relationship information that may 
 * be rendered by user agents in a variety of ways (e.g., a tool-bar with a 
 * drop-down menu of links)." HTML 4.01 spec.
 * @link http://www.w3.org/TR/html401/struct/links.html#edef-LINK
 */
function rel($href, $type, $rel = null)
{
	$attrs['type'] = $type;
	if(!is_null($rel)) $attrs['rel'] = $rel;

	if($type == 'text/javascript')
	{
		$attrs['src'] = $href;
		return tag('script', false, null, null, null, $attrs);
	}
	else
	{
		$attrs['href'] = $href;
		return tag('link', true, null, null, null, $attrs);
	}
}

/**
 * Generic inclusion: the OBJECT element
 * 
 * @param	String	$data	reference to object's data		
 * @param	Array	$params	List of Name => Value pairs for Object initialization parameters.
 * @param	String	$alt	alternate object renderings.
 * @param	String	$class 	This attribute assigns a class name or set of class 
 * 		names to an element. Any number of elements may be assigned the same 
 * 		class name or names. Multiple class names must be separated by white 
 * 		space characters.
 * @param	String	$id		This attribute assigns a name to an element. This 
 * 		name must be unique in a document.
 * @param	Array	$attrs	Array of other attributes where the key is the 
 * 		attribute and the value is the value.
 * 
 * @return 	String	HTML Object tag.
 * @package WebGizmo
 **/
function object($data, $params = array(), $alt = '', $class = '', $id = '', $attrs = array()) {
	
	$attrs['data'] = $data;
	
	$obj_init = '';
	foreach($params as $name => $value)
	{
		$obj_init .= param($name, $value);
	}
	
	return tag('object', false, $obj_init.$alt, $class, $id, $attrs);
}

/**
 * Object initialization: the PARAM element
 * 
 * @param	String	$name	Property name.
 * @param	String	$value	Property value.
 * @param	String	$id		Document-wide unique id.
 * @param	Array	$attrs	Array of other attributes where the key is the 
 * 		attribute and the value is the value.
 * 
 * @return 	String	HTML PARAM tag.
 */
function param($name, $value, $id = '', $attrs = array())
{
	$attrs['name'] = $name;
	$attrs['value'] = $value;
	
	return tag('param', true, '', '', $id, $attrs);
}

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
	
	return "\t".tag('video', false, '', $class, $id, $attrs)."\n";
}

/**
 * @return String	A well formed HTML 5 Audio tag.
 * @package WebGizmo
 **/
function audio($source, $class = '', $id = '', $attrs = array(), $not_supported_message = '') {
	
	$attrs['src'] = $source;
	
	return tag('audio', false, '', $class, $id, $attrs);
}

/**
 * "The section element represents a generic section of a document or application. A 
 * section, in this context, is a thematic grouping of content, typically with a heading.
 *
 * 	Examples of sections would be chapters, the various tabbed pages in a tabbed dialog 
 * box, or the numbered sections of a thesis. A Web site's home page could be split into 
 * sections for an introduction, news items, and contact information.
 *
 *	Authors are encouraged to use the article element instead of the section element when 
 * it would make sense to syndicate the contents of the element.
 *
 *	The section element is not a generic container element. When an element is needed for 
 * styling purposes or as a convenience for scripting, authors are encouraged to use the 
 * div element instead. A general rule is that the section element is appropriate only if 
 * the element's contents would be listed explicitly in the document's outline."
 * 
 * @link http://www.whatwg.org/specs/web-apps/current-work/multipage/sections.html#the-section-element
 * @return String	HTML Section tag
 **/
function section($content, $class = '', $id = '', $attrs = array())
{
	return tag('section', false, $class, $id, $attrs);
}

