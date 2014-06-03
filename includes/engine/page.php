<?php
	require_once("conf.php");
	set_include_path($_SERVER['DOCUMENT_ROOT'].$BASE_DIRECTORY."../");	
	$parsed_url = parse_url($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

	$path_items = array_splice(explode("/", $parsed_url['path']), substr_count($BASE_DIRECTORY, "/"));
	if($path_items[0] == "" || sizeof($path_items) < 2) $path = "pages/index.php";
	else {
		$page = preg_replace('[^A-Za-z0-9_]',"",isset($path_items[0])?$path_items[0]:"");
		$subpage = preg_replace('[^A-Za-z0-9_]',"",isset($path_items[1])?$path_items[1]:"");
		$file = preg_replace('[^A-Za-z0-9_\.]',"",isset($path_items[2])?$path_items[2]:"");
		
		if($file) {
			$path = $path = "pages/".$page."/".$subpage."/".$file;
			header('Content-type: text/javascript');
		}
		else
			$path = "pages/".$page."/".$subpage."/".$subpage.".php";
	}

	@include_once($path);
?>