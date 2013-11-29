<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Table of Contents jQuery plugin
 * 
 * @link http://fuelyourcoding.com/scripts/toc/
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class Toc extends FSFile
{
	function html($format = 'html')
	{
		$fs = FS::get();
		$id = $this->getID();
		$fs->addRef(JQUERY_URL);
		$fs->addRef(PLUGINS_URL.'/toc/jquery.tableofcontents.min.js');
		$fs->add('<script> $(document).ready(function(){ $("div#'.$id.'").tableOfContents() }) </script>');
		return ol(array(), 'Toc', $id);
	}
}