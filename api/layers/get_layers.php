<?php
	$collectionJSON = "";
	$layer = Layer::getObject($_REQUEST['l']);

	if($layer->getItemID() == $layer) {
		$collectionJSON = $layer->toJSON();
	}

	$collectionJSON = $layer->toJSON();
	
	$callback = $_REQUEST['callback'];
	if($callback)
		echo($callback.'('.$collectionJSON.');');
	else
		echo($collectionJSON);
?>