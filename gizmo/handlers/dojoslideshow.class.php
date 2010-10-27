<?php

/**
* Dojo Sildeshow hack
* @link http://articles.tutorboy.com/dojo/image-slideshow.html
*/
class DojoSlideshow extends FSDir
{
	function html($format = 'html')
	{
		FS::get()->add('
		<script>
			dojo.require("dojo.NodeList-traverse");
		
			var count = 0;
			var currentItem = 1;
		
			dojo.ready(function()
			{
				count = dojo.query("img", "DOJO-SLIDESHOW").length;
				dojo.query("#DOJO-SLIDESHOW img").first().addClass("active-image");
				setInterval("dojoSlideShow()", 3000);
			});
		
			function dojoSlideShow()
			{	
				var active = dojo.query(".active-image");
				var next = active.next("img");
				active.removeClass("active-image");
				if(next == "") {
					currentItem = 0;
					next = dojo.query("#DOJO-SLIDESHOW img").first();
				}
				currentItem++;
				next.addClass("active-image");
			}
		</script>
		<style>
			#DOJO-SLIDESHOW {
				position:relative;
				width:260px;
				height:500px;
			}
			#DOJO-SLIDESHOW img {
				z-index:0;
				display:inline;
				position:absolute;
				visibility: hidden;
			}
			#DOJO-SLIDESHOW img.active-image{
				z-index:1000!important;
				visibility: visible;
			}
		</style>
		');

		$out = '';
		
		foreach($this->getContents() as $File) 
			$out .= "\n".$File->html();

		return div($out, null, 'DOJO-SLIDESHOW');
	}
}
