<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Tweetable is a lightweight jQuery plugin which enables you to display your twitter feed on your site quickly and easily.
 * 
 * @link http://theodin.co.uk/blog/jquery/tweetable-1-6-launched.html
 *
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class Tweetable extends GizFilePlugin
{
	function html($format = 'html')
	{
		$fs = FS::get();
		
		$fs->addRef(JQUERY_URL);
		$fs->addRef('http://plugins.theodin.co.uk/jquery/tweetable/tweetable.1.6/jquery.tweetable.js');
		
		$fs->add('
			<script type="text/javascript">
				$(document).ready(function() { $("#'.$this->getID().'").tweetable({username: "'.$this->getCleanName().'", time: true, limit: 1, replies: true, position: "append"}) });
			</script>
		');		
				
		return div('', 'Tweetable', $this->getID());
	}
}
