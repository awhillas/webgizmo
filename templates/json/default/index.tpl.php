<?php 
	// Dojo ItemFileReadStore format 
	
	$json = array();
	
	foreach($fs->getContent(array('isFile' => true)) as $item)
	{
		$json[] = '
		{
			"thumb":"'.$item->get()->getFilename().'",
			"large":"'.$item->get()->getFilename().'",
			"title": "'.$item->getCleanName().'" ?>,
			"link":"'.$item->getURL().'"
		}';
	}
?>
{ items: [
	<?php echo implode(",\n", $json)?>

]}