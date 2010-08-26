<?php
class DirContent extends FileContent
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