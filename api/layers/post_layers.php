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
		$statement->setContent($contributionData['content']);
		$statement->setContext($contributionData['context']);
		$statement->setContributionID($contribution->getItemID());
		$statement->save();

		$argument = new Argument();
		$argument->setContent($contributionData['argument']);
		$argument->setSummary($contributionData['summary']);
		$argument->setContributionID($contribution->getItemID());
		$argument->save();
	}

	// Is there a URL?
	if($_REQUEST['url']) {
		// Cookie Jar
		$ckfile = tempnam ("/tmp", "CURLCOOKIE");
		
		// Clean the URL
		$url = isset($_REQUEST['url'])?$_REQUEST['url']:"";
		$url = substr($url,0,7) == "http://"?$url:"http://".$url;
		$url = getFinalUrl($url);
		
		// CURL
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile); 
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$html = curl_exec($ch);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$data = 'data:'.$content_type.';base64,'.base64_encode($html);
		curl_close($ch);
		
		// Remove all meta tags
		$html = preg_replace('/\<\s*meta[^>]*>/i',"", $html);

		// Remove all iframes (force a "noscript" environment)
		$html = preg_replace('/\<\s*iframe[^>]*?>.*?\<\s*\/iframe[^>]*?>/is',"", $html);

		// Remove all scripts (force a "noscript" environment)
		$html = preg_replace('/<\s*script[^>]*>.*?<\s*\/script[^>]*?>/is',"", $html);
		$html = preg_replace('/<\s*noscript[^>]*>/i',"", $html);
			
		$data = preg_replace('/src=\".*\"(.*)dest_src=\"(.*)\"/i',"src=\"\\2\" \\1", $data);
		$data = preg_replace('/dest_src=\"(.*)\"(.*)src=\".*\"/i',"src=\"\\1\" \\2", $data);

		// General Image Fixes
		$html = preg_replace('/src=\"\/(.*?)\"/i', "src=\"".$url_base."/\\1\"", $html);
		$html = preg_replace('/src=\'\/(.*?)\'/i', "src='".$url_base."/\\1'", $html);

		// General Link Fixes
		$html = preg_replace('/href=\"\/(.*?)\"/i', "href=\"".$url_base."/\\1\"", $html);
		$html = preg_replace('/href=\'\/(.*?)\'/i', "href='".$url_base."/\\1'", $html);

		// CSS Link Fixes
		$html = preg_replace('/\<link(.*?)text\/css(.*?)src\=(.*?)\/\>/i', "<link\\1text/css\\2href=\\3/>", $html);
	} elseif ($_REQUEST['text']) {
		$html = "<html><head></head><body>".str_replace("\n","<br />",$_REQUEST['text'])."</body></html>";
	}
	$script = "
	
	<script src='".$BASE_DIRECTORY."js/goggles.js'></script><script>truthGoggles({server: '".$GOGGLES_DIRECTORY."',layerId: ".$layer->getItemID()."});</script>";


	$html = preg_replace('/<\s*\/\s*head[^>]*>/', $script."</head>", $html);

	$guid = uniqid("");
	$file = fopen($_SERVER['DOCUMENT_ROOT'].$BASE_DIRECTORY."layers/".$guid.".html","w");
	fwrite($file, $html);

	$collectionJSON = '{
		"layer": '.$layer->toJSON().',
		"url": "'.$BASE_DIRECTORY."layers/".$guid.'"
	}';
	
	if(isset($jsonp) && $jsonp)
		echo($callback.'('.$collectionJSON.');');
	else
		echo($collectionJSON);
?>