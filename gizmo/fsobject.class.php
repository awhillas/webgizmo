<?php
/**
 * File System Object class
 * 
 * A general wrapper for a File System Object. 
 *
 * @package Web Gizmo
 * @author Alexander R B Whillas
 **/
class FSObject extends SplFileInfo
{
	/**
	 * Holds the file object
	 *
	 * @var SplFileInfo Object
	 **/
//	private $_file;
	
	public function __construct($path)
	{
		if(!is_a($path, 'SplFileInfo'))
		{
			$path = $File->getRealPath();
		}
		parent::__construct($path);
	}

	/**
	 * Factory function to determine the Handler for the given system path.
	 * Can be a path to a file or a directory.
	 *
	 * @param	$path	String | SplFileInfo	The absolute (Real) path to the file.
	 * @return 	void
	 **/
	public static function make($File)
	{
		if(!is_a($File, 'SplFileInfo'))
		{
			if(is_string($File))
			{
				if(file_exists($File))
					$File = new SplFileInfo($File);
				else
				{
					trigger_error('File does not exist?  '.print_r($File, TRUE));

					return null;					
				}
			}
			else
			{
				trigger_error('Expecting file path (String) or File (SplFileInfo), passed: '.print_r($File, TRUE));
				
				return null;
			}
		}
		
		$extention = FSObject::getExtention($File->getbaseName());
		
		// Handler class to use if we don't find one.
		$fallback_handler = 'GenericFileContent';
		
		if(!$extention)
		{
			// NO extention so look up generic handler class for the general type.
			if($File->isDir())
			{
				$extention = 'dir';
				$fallback_handler = 'DirContent';
			}
			else
			{
				$extention = 'file';
			}
		}

		// Lookup content map for file hander			
		if($handler_class = HandlerMap::get()->lookup($extention))
		{
			return new $handler_class($File);
		}
		else
		{
			trigger_error("Unknown extension type $extention!");

			return new $fallback_handler($File);
		}
	}

	/**
	 * Get the content of the file or directory
	 * 
	 * @return Mixed
	 **/
	public function getContents()
	{
		return null;
	}
	
	public static function getExtention($filename)
	{
		$parts = explode('.', $filename);

		if(count($parts) > 1)
		{
			return end($parts);
		}
		else
			return false;
	}
	
	/**
	 * Parse the file name for _tags_
	 *
	 * @return Array	List of tags found in the file name.
	 * @todo Implement.
	 **/
	function getTags($filename = '')
	{
		return array();
	}
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //
	// Abstract funcitons
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //
	

}