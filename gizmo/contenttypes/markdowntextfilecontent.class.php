<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * The markdown library
 */
include_once(INCLUDES_PATH.'/PHP Markdown/markdown.php');

/**
 * Markdown Text files
 * 
 * Uses the PHP Markdown lib for smarter conversion of text to HTML
 *
 * @package	Web Gizmo
 * @link http://michelf.com/projects/php-markdown/
 **/
class MarkdownTextFileContent extends TextFileContent
{		
	function html($format = 'xhtml1.1')
	{
		return $this->renderHTML($format, Markdown($this->_content));
	}
}
