<?PHP
	set_include_path($_SERVER['DOCUMENT_ROOT']);
	require_once("conf.php");
	header('Content-type: text/javascript');
	
	$params = explode('/',(isset($_GET['q'])?$_GET['q']:""));
	$resourceType = preg_replace('[^A-Za-z0-9_]',"",isset($params[0])?$params[0]:"");
	$requestMethod = preg_replace('[^A-Za-z0-9_]',"",strtolower($_SERVER['REQUEST_METHOD']));
	
	$resourceIdentifier = isset($params[1])?$params[1]:"";
	
	$jsonp = isset($_GET['jsonp'])?true:false;
    $callback = isset($_GET['callback'])?$_GET['callback']:"goggles_".$requestMethod."_".$resourceType."_callback";
    $resourceIdentifier = isset($_GET['r'])?$_GET['r']:null|$resourceIdentifier|null;
	
	$path = "api/".$resourceType."/".$requestMethod."_".$resourceType.".php";
	if(file_exists($path))
		include_once($path);
	else {
		echo("ERR: Invalid API Call");
	}
?>