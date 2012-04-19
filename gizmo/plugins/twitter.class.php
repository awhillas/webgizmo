<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Remy sharp's
 * 
 * @link http://code.google.com/p/twitterjs/
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class Twitter extends FSDir
{
	function html($format = 'html')
	{
		$out = '';
		
		$id = $this->getID();
		
		$fs = FS::get();
		
		$fs->addRef('http://twitterjs.googlecode.com/svn/trunk/src/twitter.min.js');
		
		$fs->add('
		<script type="text/javascript" charset="utf-8">
			getTwitters("'.$id.'", { id: "'.$this->getName().'", count: 1 });
		</script>		
		');

		return div($out, 'Twitter', $id);
	}
}
