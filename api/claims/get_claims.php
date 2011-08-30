<?PHP
	set_include_path($_SERVER['DOCUMENT_ROOT']);
	require_once("conf.php");
	include_once("models/Claim.php");
	
	$collectionJSON = "";
	if($resourceIdentifier) {
		// Return a single object
		$object = Claim::getObject((int)$resourceIdentifier);
		$collectionJSON = '{"claims": ['.$object->toJSON()."]}";
	} else {
		// Return a list of objects
		if (false && isset($_GET['claim_id'])) {
		} else {
			$claims = Claim::getObjects('select claims.id from claims');
			
			// Run through the claims and generate JS Objects
			$objectJSON = array();
			foreach($claims as $claim)
				$objectJSON[] = $claim->toJSON();
			
			$collectionJSON = '{"claims": ['.implode(",", $objectJSON).']}';
		}
	}
	
	if(isset($jsonp) && $jsonp)
		echo($callback.'('.$collectionJSON.');');
	else
		echo($collectionJSON);
?>