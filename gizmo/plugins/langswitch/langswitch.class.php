<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Lang - Language Switch - Languages Witch
 * 
 * Can render a list of language links for UI language switching in HTML
 * 
 * @package	WebGizmo
 * @subpackage	GizPlugins
 * @todo Make language switching work and document 
 */
class LangSwitch extends GizPlugin
{
	private $_lang_list;
	
	/**
	 * @param	$languages_list	Array	on the format array('en' => 'English', 'de' => 'Deutsch')
	 */
	public function __construct($languages_list)
	{
		$this->_lang_list = $languages_list;
	}
	
	/**
	 * Factory method for making LangSwitches
	 */
	static public function make($languages_list)
	{
		return new LangSwitch($languages_list);
	}
		
	/**
	 * 
	 */
	public function html()
	{
		$list = array();
		
		$FS = FS::get();
		
		// Gte the current language
		$current = $FS->getLanguage();	
		
		$current_index = $this->buildDirIndex(WEB_ROOT . $content_path. $current);

		foreach($this->_lang_list as $lang => $text)
		{
			if($lang == $current)
				$list[$lang.' Current'] = $text;
			else
			{
				// We want a link to the equivalent folder in the other language
				// use the leading sort number (i.e. ##_ ) to figure out which that is
				
				$lang_index = $this->buildDirIndex(WEB_ROOT . $content_path. $lang);
				
				$list[$lang] = a('?lang='.$lang, $text);
			}
		}
		
		return ul($list, 'LangaugeSwitch');
	}
	
	/**
	 * Builds a list of the folder hierarchy indexed by sorting prefix '##_'
	 * So paths can be looked up like: $path[02][69]
	 */
	private function buildDirIndex($path)
	{
		foreach(FS::getDirectoryTree(new Path($path)) as $folder => $subfolders)
		{
			if(preg_match('/^([0-9]{2})_/', $folder, $index))
				$out[$index[0]] = $folder
		}
	}
}
