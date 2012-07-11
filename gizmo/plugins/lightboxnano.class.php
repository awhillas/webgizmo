<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * The LightboxNano is a very lightweight lightbox which only displays a larger image
 * 
 * @link http://www.dojotoolkit.org/reference-guide/dojox/image/LightboxNano.html
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class LightboxNano extends FSDir
{
	function html($format = 'html')
	{
		$fs = FS::get();
		
		//$fs->addRef('http://ajax.googleapis.com/ajax/libs/dojo/1.4/dojo/dojo.xd.js');
		$fs->addRef(DOJOTOOLKIT_URL);
				
		$fs->add('
			<script type="text/javascript">
			    dojo.require("dojox.image.LightboxNano");
				dojo.require("dojo.parser");
			</script>
		');
		
		$LargeImage = $this->getPath()->add('_large_');
		
		// <a dojoType="dojox.image.LightboxNano" href="/path/to/large/image.jpg">
		//     <img src="/path/to/small/image.jpg">
		// </a>
		
		// Images sorted by file size
		$imgs = $this->images()->run('size');

		return a(
			$imgs->last()->getFileURL(),
			$imgs->first()->html(), 
			'LightboxNano', 
			$this->getID(), 
			array('dojoType' => 'dojox.image.LightboxNano')
		);
	}
}
