<?php
/**
 * @package WebGizmo
 * @author Alexander Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * Filtered Directory Iterator
 *
 * @package WebGizmo
 **/
class FilteredRecursiveDirectoryIterator extends FSFilterIterator
{	
    public function __construct($path, $filters = array())
    {
        parent::__construct(new RecursiveDirectoryIterator($path));
    }
}
