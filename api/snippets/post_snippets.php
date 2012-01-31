<?PHP
	set_include_path($_SERVER['DOCUMENT_ROOT']);
	require_once("conf.php");
	include_once("models/Claim.php");
	include_once("models/Snippet.php");
	
	$collectionJSON = "";
	if($resourceIdentifier) {
		// Return a single object
		$object = Snippet::getObject((int)$resourceIdentifier);
		$collectionJSON = '{"snippets": ['.$object->toJSON().']}';
	} else {
		// Return a list of objects
		if(isset($_POST['url'])) {
			// Curl the URL and pull any snippets that are contained
		} elseif (isset($_POST['context'])) {
			// Look at each piece of context and pull any snippets that are contained
			$context = utf8_decode($_POST['context']);
			$oid = isset($_POST['oid'])?$_POST['oid']:0;
			
			// Scan the snippet DB for known matches
			$snippets = Snippet::getSnippetsByContext($context);
			
			// Run through the snippets and generate JS Objects
			$snippetJSON = array();
			foreach($snippets as $snippet) {
				$contentRegExp = '/'.preg_replace('/\s+/','\\s+',$snippet->getContent()).'/';
				$contentSplit = preg_split($contentRegExp, $context, null, PREG_SPLIT_OFFSET_CAPTURE);
				$contentStart = strlen($contentSplit[0][0]);
				
				for($x = 1; $x < sizeof($contentSplit) ; $x += 1 ) {
					$contentLength = $contentSplit[$x][1] - $contentStart;
					$snippetJSON[] = $snippet->toJSON($contentStart, $contentLength);
					
					// Update the content start for the next instance of the snippet (if it exists)
					$contentStart = $contentSplit[$x][1] + strlen( $contentSplit[$x][0]);
				}
			}
			
			// Run through the related claims and generate JS Objects
			$relatedClaimsJS = array();
			
			// Create the collection
			$collectionJSON = '{
				"oid": '.DBConn::clean($oid).',
				"snippets": ['.implode(",", $snippetJSON).']
			}';
		
		} elseif (isset($_POST['claim_id'])) {
			// Load a claim and return it's snippets
			$claimID = $_POST['claim_id'];
			$claim = Claim::getObject($claimID);
			$snippets = $claim->getSnippets();
			
			// Run through the snippets and generate JS Objects
			$snippetJSON = array();
			foreach($snippets as $snippet) {
				$snippetJSON[] = $snippet->toJSON();
			}
		
			$collectionJSON = '{
				"snippets": ['.implode(',', $snippetJSON).']
			}';
		} else {
			$snippets = Snippet::getObjects('select snippets.id from snippets');
			
			// Run through the snippets and generate JS Objects
			$snippetJSON = array();
			foreach($snippets as $snippet) {
				$snippetJSON[] = $snippet->toJSON();
			}
		
			$collectionJSON = '{
		    	"snippets": ['.implode(',', $snippetJSON).']
			}';
		}
	}
	
	if(isset($jsonp) && $jsonp)
		echo($callback.'('.$collectionJSON.');');
	else
		echo($collectionJSON);
?>