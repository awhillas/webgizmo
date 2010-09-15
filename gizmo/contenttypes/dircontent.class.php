<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Handler for a folder
 *
 * @package WebGizmo
 **/
class DirContent extends FSDir
{	
	function html($format = 'xhtml1.1')
	{
		return '
		<div class="DirContent">
			<a href="' . $this->getURL() . '">'. $this->getCleanName() .'</a>
		</div>
		';
	}
}