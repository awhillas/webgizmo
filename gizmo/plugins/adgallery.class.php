<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * A highly customizable gallery/showcase plugin for jQuery.
 * 
 * @link http://coffeescripter.com/code/ad-gallery/
 * @package WebGizmo
 * @author Alexander R B Whillas
 */
class CycleLite extends FSDir
{
	
	function html()
	{
		$fs = FS::get();
		
		$fs->addRef('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
		$fs->addRef('http://coffeescripter.com/code/ad-gallery/jquery.ad-gallery.js');
		$fs->addRef('http://coffeescripter.com/code/ad-gallery/jquery.ad-gallery.css');
		
		$fs->add('
			<script type="text/javascript">
				
			</script>
		');
		
		$out = '';
/*
<div class="ad-gallery">
  <div class="ad-image-wrapper">
  </div>
  <div class="ad-controls">
  </div>
  <div class="ad-nav">
    <div class="ad-thumbs">
      <ul class="ad-thumb-list">
        <li>
          <a href="images/1.jpg">
            <img src="images/thumbs/t1.jpg" title="Title for 1.jpg">
          </a>
        </li>
        <li>
          <a href="images/2.jpg">
            <img src="images/thumbs/t2.jpg" longdesc="http://www.example.com" alt="Description of the image 2.jpg">
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
*/
		foreach($this->getContents() as $File) 
			$out .= $File->html()."\n";
		
		return div("\n".$out, 'CycleLite', $this->getID());
	}
}
