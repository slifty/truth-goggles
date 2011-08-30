<?PHP
	set_include_path($_SERVER['DOCUMENT_ROOT']);
	require_once("conf.php");
	include_once("models/CorpusItem.php");
	
	$collectionJSON = "";
	if($resourceIdentifier) {
		// Return a single object
		$object = CorpusItem::getObject((int)$resourceIdentifier);
		$collectionJSON = '{"corpus_items": '.$object->toJSON().'}';
		
	} else {
		// Return a list of objects
		if(isset($_GET['claim_id'])) {
			// Get corpus items associated with a claim
			
			// Load in the claim
			$claim = Claim::getObject($_GET['claim_id']);
			$corpusItems = $claim->getCorpusItems();
			
			// Run through the corpus items and generate JS Objects
			$objectJSON = array();
			foreach($corpusItems as $corpusItem)
				$objectJSON[] = $corpusItem->toJSON();
			
			$collectionJSON = '{"corpus_items": ['.implode(",", $objectJSON).']}';
			
		} else {
			// Return all corpus items
		}
		
	}	
	
	if(isset($jsonp) && $jsonp)
		echo($callback."(".$collectionJSON.");");
	else
		echo($collectionJSON);
?>