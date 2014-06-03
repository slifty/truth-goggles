<?php
	set_include_path('../');
	if(array_key_exists('api', $_GET)) {
		require_once('../includes/engine/api.php');
	} else {
		require_once('../includes/engine/page.php');
	}
?>