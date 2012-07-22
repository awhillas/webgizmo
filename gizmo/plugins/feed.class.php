<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * jFeed: jQuery feed parser plugin
 * 
 * @link https://github.com/jfhovinne/jFeed
 * 
 * @package WebGizmo
 * @subpackage	GizPlugins
 * @author Alexander R B Whillas
 */
class Feed extends FSFile
{
	function html($format = 'html')
	{
		$fs = FS::get();
		$id = $this->getID();
		$path = $this->getPath()->less(FS::get()->contentRoot()->get());
		$fs->addRef(JQUERY_URL);
		//$fs->addRef('https://raw.github.com/jfhovinne/jFeed/master/build/dist/jquery.jfeed.pack.js');
		$fs->addRef(PLUGINS_URL.'/feed/jquery.jfeed.pack.js');
		$fs->add('<script>
			jQuery(function() {

				jQuery.getFeed({
					url: "'.GIZMO_PLUGIN_URL_PREFIX.'/feed'.$path.'",
					success: function(feed) {

						jQuery("#'.$id.'")
							.append("<h2><a href=\"" + feed.link + "\">" + feed.title + "</a></h2>");

						var html = "<ol>";

						for(var i = 0; i < feed.items.length && i < 5; i++) {

							var item = feed.items[i];
							html += "<li>"
							+ "<a href=\""+item.link+"\">" + item.title + "</a>"
							+ "<div class=\"description\">" + item.description + "</div>"
							+ "<small>" + item.updated + "</small>" 
							+ "</li>";
						}
						html += "</ol>";
						
						jQuery("#'.$id.'").append(html);
					}	 
				});
			});		
		</script>');
		
		return div('', 'Feed', $id);
	}

	function url($Path)
	{
		$File = new Path(FS::get()->contentRoot() . $Path);
		if($File->is())
		{
			header('Content-Type: text/xml; charset=utf-8');
			$feed_url = trim(file_get_contents($File->get()));
			echo file_get_contents($feed_url);
		}
		else
		{
			header("HTTP/1.0 404 Not Found");
			echo '404: Bad path';
		}
	}
}