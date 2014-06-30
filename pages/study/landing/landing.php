<?php
	session_start();
	$order = str_split($_GET['t']);

	$_SESSION['order'] = $order;

	header( 'Location: '.$BASE_DIRECTORY.'study/next' ) ;
?>
