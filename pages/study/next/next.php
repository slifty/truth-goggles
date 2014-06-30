<?php
	session_start();
	$next = array_pop($_SESSION['order']);

	if(array_key_exists("current", $_SESSION))
		$_SESSION["previous"] = $_SESSION["current"];
	else
		$_SESSION["previous"] = null;
	$_SESSION["current"] = $next;

	$target = $BASE_DIRECTORY;
	switch($next) {
		case "1":
			$target .= "study/gmos1";
			$_SESSION['previous_topic'] = "GMOs";
			array_push($_SESSION['order'], 'survey');
			break;
		case "2":
			$target .= "study/gmos2";
			$_SESSION['previous_topic'] = "GMOs";
			array_push($_SESSION['order'], 'survey');
			break;
		case "3":
			$target .= "study/gmos3";
			$_SESSION['previous_topic'] = "GMOs";
			array_push($_SESSION['order'], 'survey');
			break;
		case "4":
			$target .= "study/guns4";
			$_SESSION['previous_topic'] = "guns";
			array_push($_SESSION['order'], 'survey');
			break;
		case "5":
			$target .= "study/guns5";
			$_SESSION['previous_topic'] = "guns";
			array_push($_SESSION['order'], 'survey');
			break;
		case "6":
			$target .= "study/guns6";
			$_SESSION['previous_topic'] = "guns";
			array_push($_SESSION['order'], 'survey');
			break;
		case "final":
			$target .= "study/final";
			break;
		case "survey":
			$target .= "study/survey";
			break;
		default:
			$target .= "study/thanks";
	}
	header( 'Location: '.$target ) ;

?>