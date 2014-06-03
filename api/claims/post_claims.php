<?PHP
	
	$collectionJSON = "";
	if($resourceIdentifier) {
		// Return a single object
		$object = Claim::getObject((int)$resourceIdentifier);
		$collectionJSON = '{"claims": ['.$object->toJSON()."]}";
	} else {
		// Return a list of objects
		if (isset($_POST['context'])) {
			$context = utf8_decode($_POST['context']);
			$oid = isset($_POST['oid'])?$_POST['oid']:0;
			
			
			
			// Get a narrow list of possible snippets
			$possible_snippets = Snippet::getObjectsByContext($context, Snippet::MATCH_LOOSE);
			
			// Prime the pump
			$stop_words = Token::getStopWords();
			$token_strings = Token::tokenize($context, Token::TOKEN_ALL);
			$match_tracker = array();
			$x = 0;
			foreach($possible_snippets as $possible_snippet) {
				$match_tracker[$x++] = array(
					"snippet" => $possible_snippet,
					"snippet_text" => Token::codify($possible_snippet->getContent())." ",
					"word_count" => sizeof(Token::tokenize($possible_snippet->getContent())),
					"non_stop_word_count" => sizeof(array_diff(Token::tokenize($possible_snippet->getContent()), $stop_words)),
					"matches" => array_pad(array(), sizeof($token_strings), 0),
					"non_stop_matches" => array_pad(array(), sizeof($token_strings), 0)
				);
			}
			
			// Go through each word of the text and mark down the match ranges
			for($x = 0 ; $x < sizeof($token_strings) ; $x++) {
				for($y = 0 ; $y < sizeof($match_tracker) ; $y++) {
					if(strpos($match_tracker[$y]["snippet_text"], $token_strings[$x]." ") === false)
						continue;
					
					// Check if this is the beginning of a perfect match
					$end = min(sizeof($token_strings), $x + $match_tracker[$y]["word_count"]);
					for($z = $x, $compare_string = "" ; $z < $end ; $z++) $compare_string .= $token_strings[$z]." ";
					if($compare_string == $match_tracker[$y]["snippet_text"]) {
						// Remove its effect -- we already know about perfect matches
						$match_tracker[$y]["matches"][$x] -= $match_tracker[$y]["word_count"];
						$match_tracker[$y]["non_stop_matches"][$x] -= $match_tracker[$y]["non_stop_word_count"];
						for($i = 1; $i < $match_tracker[$y]["word_count"]; $i++) {
							if($x-$i >= 0) {
								$match_tracker[$y]["matches"][$x - $i] -= ($match_tracker[$y]["word_count"] - $i);
								$match_tracker[$y]["non_stop_matches"][$x - $i] -= ($match_tracker[$y]["non_stop_word_count"] - $i);
							}
							if($x+$i < sizeof($token_strings)){
								$match_tracker[$y]["matches"][$x + $i] -= ($match_tracker[$y]["word_count"] - $i);
								$match_tracker[$y]["non_stop_matches"][$x + $i] -= ($match_tracker[$y]["non_stop_word_count"] - $i);
							}
						}
					}
					
					$end = max(0, ($x - $match_tracker[$y]["word_count"]));
					for($z = $x ; $z > $end ; $z--) {
						$match_tracker[$y]["matches"][$z]++;
						if(!in_array($token_strings[$x], $stop_words)) $match_tracker[$y]["non_stop_matches"][$z]++;
					}
				}
			}
			
			// We now have a vector of hot spots, find the top phrase candidates
			$finalists = array();
			for($x = 0 ; $x < sizeof($match_tracker) ; $x++) {
				$non_stop_cutoff = max(2, floor($match_tracker[$x]["non_stop_word_count"] * .5));
				$cutoff = floor($match_tracker[$x]["word_count"] * .6);
				$match_start = -1;
				$match_end = -1;
				for($y = 0 ; $y < sizeof($token_strings) ; $y++) {
					if($match_end != -1) {
						$finalists[] = array(
							"claim" => $match_tracker[$x]["snippet"]->getClaim(),
							"match_start" => $match_start,
							"match_end" => $match_end
						);
						$match_start = -1;
						$match_end = -1;
					}
					
					if(($match_tracker[$x]["matches"][$y] >= $cutoff)
					&& ($match_tracker[$x]["non_stop_matches"][$y] >= $non_stop_cutoff)) {
						if($match_start == -1) {
							$match_start = $y;
						}
					}
					elseif($match_start != -1)
						$match_end = $y + $match_tracker[$x]["matches"][$y];
					
				}
			}
			
			
			// We now have the top phrase candidates, find out the actual character start and end and store the json
			$claimJSON = array();
			foreach($finalists as $finalist) {
				$context = preg_replace("/(\s+)([^\w\s]+)(\s+)/e",'"\\1" . str_repeat(" ", strlen ("\\2")) . "\\3"', $context);
				$contentRegExp = "/((\S+\s+){".$finalist['match_start']."})((\S+\s+){".($finalist['match_end'] - $finalist['match_start'])."})/";
				preg_match($contentRegExp, $context, $matches, PREG_OFFSET_CAPTURE);
				$contentStart = $matches[3][1];
				$contentLength = strlen(rtrim($matches[3][0]));
				$claimJSON[] = $finalist["claim"]->toJSON($contentStart, $contentLength);
			}
			
			
			// Create the collection
			$collectionJSON = '{
				"oid": '.DBConn::clean($oid).',
				"claims": ['.implode(",", $claimJSON).']
			}';
			
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