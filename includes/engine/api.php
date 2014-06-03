<?PHP
	require_once("conf.php");
	set_include_path($_SERVER['DOCUMENT_ROOT'].$BASE_DIRECTORY."../");
	header('Content-type: text/javascript');
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Content-Type');

	$parsed_url = parse_url($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	$path_items = array_splice(explode("/", $parsed_url['path']), substr_count($BASE_DIRECTORY, "/") + 1);

	$resourceType = preg_replace('[^A-Za-z0-9_]',"",isset($path_items[0])?$path_items[0]:"");
	$requestMethod = preg_replace('[^A-Za-z0-9_]',"",strtolower($_SERVER['REQUEST_METHOD']));
	
	$resourceIdentifier = isset($path_items[1])?$path_items[1]:"";
	
	$jsonp = isset($_GET['jsonp'])?true:false;
	$callback = isset($_GET['callback'])?$_GET['callback']:"goggles_".$requestMethod."_".$resourceType."_callback";
	$resourceIdentifier = isset($_GET['r'])?$_GET['r']:null|$resourceIdentifier|null;
	
	$path = "api/".$resourceType."/".$requestMethod."_".$resourceType.".php";
	
	include_once($path);
?>