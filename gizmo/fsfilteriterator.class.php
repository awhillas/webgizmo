<?php

class FSFilterIterator extends FilterIterator
{
	/**
	 * Implement abstract function from FilterIterator
	 *
	 * @return boolean TRUE and the file passes, FALSE and its filtered out.
	 **/
    public function accept()
    {	
		$current = $this->current();

		
		
        return true;
    }
}