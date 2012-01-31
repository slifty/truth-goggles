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
		if (isset($_POST['context'])) {
			// Get a list of related claims from the API
			$ch = curl_init($API_RELATED_CLAIMS.'?c='.urlencode($_POST['context']));
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
			
			$collectionJSON = curl_exec($ch);
			curl_close($ch);
			
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