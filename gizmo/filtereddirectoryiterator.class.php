<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Filtered Directory Iterator
 *
 * @package WebGizmo
 **/
class FilteredDirectoryIterator extends FSFilterIterator
{	
    public function __construct($path, $filters = array())
    {
        parent::__construct(new DirectoryIterator($path));
    }
}