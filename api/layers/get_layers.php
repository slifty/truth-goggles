<?php
	$collectionJSON = "";
	$layer = Layer::getObject($_REQUEST['l']);
	$callback = $_REQUEST['callback'];

	if($layer->getItemID() == $layer) {
		$collectionJSON = $layer->toJSON();
	}

	$collectionJSON = $layer->toJSON();
	
	if($callback)
		echo($callback.'('.$collectionJSON.');');
	else
		echo($collectionJSON);
?>