<?php
/**
 * Filtered Directory Iterator
 *
 * @package default
 * @author Alexander R B Whillas
 **/
class FilteredRecursiveDirectoryIterator extends FSFilterIterator
{	
    public function __construct($path, $filters = array())
    {
        parent::__construct(new RecursiveDirectoryIterator($path), $filters);
    }
}
