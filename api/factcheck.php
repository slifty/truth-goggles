<?PHP
	header('Content-type: text/javascript');
	set_include_path("../");
	include_once("models/Snippet.php");
	$context = isset($_GET['context'])?$_GET['context']:"";
	$callback = isset($_GET['callback'])?$_GET['callback']:"factcheck_callback";
	$pid = isset($_GET['pid'])?$_GET['pid']:0;
	$verdictsJS = array();
	
	// Scan the snippet DB for low hanging fruit
	$snippets = Snippet::getSnippetsByContext($context);
	
	// Run through the snippets and generate verdict JS Objects
	foreach($snippets as $snippet) {
		$verdicts = $snippet->getClaim()->getVerdicts();
		foreach($verdicts as $verdict) {
			$resultClass = $verdict->getResultClass();
			$contentStart = strpos($context, $snippet->getContent());
			$contentLength = strlen($snippet->getContent());
			$verdictsJS[] = "
			{
				contentStart: ".DBConn::clean($contentStart).",
				contentLength: ".DBConn::clean($contentLength).",
				verdictTitle: ".DBConn::clean($resultClass->getTitle()).",
				verdictDescription: ".DBConn::clean($resultClass->getDescription()).",
				verdictClass: ".DBConn::clean($resultClass->getClass())."
			}";
		}
	}
?>
<?PHP echo($callback) ?>({
	pid: <?PHP echo($pid)?>,
	verdicts: [<?PHP echo(implode(",",$verdictsJS));?>]
});	