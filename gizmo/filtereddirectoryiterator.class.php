<?php
/**
 * Filtered Directory Iterator
 *
 * @package default
 * @author Alexander R B Whillas
 **/
class FilteredDirectoryIterator extends FSFilterIterator
{	
    public function __construct($path, $filters = array())
    {
        parent::__construct(new DirectoryIterator($path), $filters);
    }
}