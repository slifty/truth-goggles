<?php
	/* GET THE CONTENT OF THE WEB PAGE */
	$url = $_GET['url'];

	// Cookie Jar
	$ckfile = tempnam ("/tmp", "CURLCOOKIE");
	
	// Clean the URL
	$url = isset($_GET['url'])?$_GET['url']:"";
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
		

	/* EXTRACT THE TEXT */
	$Readability     = new Readability($html); // default charset is utf-8
	$readabilityData = $Readability->getContent(); // throws an exception when no suitable content is found

	$collectionJSON = '{
		"html": '.DBConn::clean($readabilityData['content']).'
	}';

	$callback = $_REQUEST['callback'];
	if($callback)
		echo($callback.'('.$collectionJSON.');');
	else
		echo($collectionJSON);
?>