<?php
	$event = new Event();
	$event->setParticipantID($_REQUEST['u']);
	$event->setType($_REQUEST['a']);
	$event->setContributionID($_REQUEST['c']);
	$event->save();
?>