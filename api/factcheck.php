<?PHP
	header('Content-type: text/javascript');
	set_include_path("../");
	include_once("models/Snippet.php");
	$context = utf8_decode(isset($_GET['context'])?$_GET['context']:"");
	$callback = isset($_GET['callback'])?$_GET['callback']:"factcheck_callback";
	$pid = isset($_GET['pid'])?$_GET['pid']:0;
	$verdictsJS = array();
	
	// Scan the snippet DB for low hanging fruit
	$snippets = Snippet::getSnippetsByContext($context);
	
	// Run through the snippets and generate verdict JS Objects
	foreach($snippets as $snippet) {
		$contentRegExp = "/".preg_replace("/\s+/","\\s+",$snippet->getContent())."/";
		$verdicts = $snippet->getClaim()->getVerdicts();
		foreach($verdicts as $verdict) {
			$contentSplit = preg_split($contentRegExp, $context, null, PREG_SPLIT_OFFSET_CAPTURE);
			$contentStart = strlen($contentSplit[0][0]);
			$contentLength = $contentSplit[1][1] - $contentStart;
			$verdictsJS[] = "
			{
				contentStart: ".DBConn::clean($contentStart).",
				contentLength: ".DBConn::clean($contentLength).",
				verdictTitle: ".DBConn::clean($verdict->getResultClass()->getTitle()).",
				verdictDescription: ".DBConn::clean($verdict->getResultClass()->getDescription()).",
				verdictClass: ".DBConn::clean($verdict->getResultClass()->getClass())."
			}";
		}
	}
?>
<?PHP echo($callback) ?>({
	pid: <?PHP echo($pid)?>,
	verdicts: [<?PHP echo(implode(",",$verdictsJS));?>]
});	
