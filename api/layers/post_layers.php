<?PHP
	$collectionJSON = "";

	// Make sure the layer has all required information
	$layerData = $_REQUEST['layer'];

	// Create the layer
	$layer = new Layer();
	$layer->save();

	foreach ($layerData['contributions'] as $contributionData) {
		$contribution = new Contribution();
		$contribution->setLayerID($layer->getItemID());
		$contribution->save();

		$question = new Question();
		$question->setContent($contributionData['question']);
		$question->setContributionID($contribution->getItemID());
		$question->save();

		$statement = new Statement();
		$statement->setContent($contributionData['statement']);
		$statement->setContext($contributionData['context']);
		$statement->setContributionID($contribution->getItemID());
		$statement->save();

		$argument = new Argument();
		$argument->setContent($contributionData['argument']);
		$argument->setSummary($contributionData['summary']);
		$argument->setContributionID($contribution->getItemID());
		$argument->save();
	}

	$collectionJSON = $layer->toJSON();
	
	if(isset($jsonp) && $jsonp)
		echo($callback.'('.$collectionJSON.');');
	else
		echo($collectionJSON);
?>