<?php
	session_start();
	$order = str_split($_GET['t']);
	array_unshift($order,"exit");

	$_SESSION['order'] = $order;

	$participant = new Participant();
	$participant->setTreatmentCode($_GET['t']);
	$participant->save();
	$_SESSION['participant_id'] = $participant->getItemID();

	header( 'Location: '.$BASE_DIRECTORY.'study/next' ) ;
?>
