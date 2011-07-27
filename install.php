<?php
set_include_path("/");
require_once("conf.php");
require_once("models/DBConn.php");

// Get connection
$mysqli = DBConn::connect();
if(!$mysqli || $mysqli->connect_error) {
	echo("Could not connect to DB.  Did you follow the install instructions in README?\n");
	die();
}

// Look up installed version
$result = $mysqli->query("select appinfo.version as version
				  			from appinfo");

if($result->num_rows == 0)
	$version = 0;
else {
	$resultArray = $result->fetch_assoc();
	$version = $resultArray['version'];
	$result->free();
}

echo("Current Version: ".$version."\n");
switch($version) {
	case 0: // Never installed before
		echo("Fresh Install...\n");
		echo("Creating appinfo table\n");
		$mysqli->query("CREATE TABLE appinfo (version varchar(8))") or print($mysqli->error);
		$mysqli->query("INSERT INTO appinfo (version) values('1');") or print($mysqli->error);
			
	case 1: // First update
		echo("Creating claims table\n");
		$mysqli->query("CREATE TABLE claims (id int auto_increment primary key,
											content text,
											date_created datetime)") or print($mysqli->error);
		echo("Creating snippets table\n");
		$mysqli->query("CREATE TABLE snippets (id int auto_increment primary key,
											claim_id int,
											url text,
											content text,
											context text)") or print($mysqli->error);
		echo("Creating result_classes table\n");
		$mysqli->query("CREATE TABLE result_classes (id int auto_increment primary key,
											title varchar(64),
											description text,
											color char(6))") or print($mysqli->error);
		echo("Creating claim_sources table\n");
		$mysqli->query("CREATE TABLE claim_sources (id int auto_increment primary key,
											title varchar(64),
											url varchar(255))") or print($mysqli->error);
		echo("Creating verdicts table\n");
		$mysqli->query("CREATE TABLE verdicts (id int auto_increment primary key,
											claim_id int,
											result_id int,
											claim_source_id int,
											date_created datetime)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo (version) values('2');") or print($mysqli->error);
		
	case 2:
		echo("Updating claims table\n");
		$mysqli->query("ALTER TABLE verdicts
						  ADD COLUMN url varchar(255) AFTER claim_source_id") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo (version) values('3');") or print($mysqli->error);
		
	case 3:
		echo("Updating result_classes table\n");
		$mysqli->query("ALTER TABLE result_classes
					     DROP COLUMN color") or print($mysqli->error);
		$mysqli->query("ALTER TABLE result_classes
						  ADD COLUMN class varchar(32) AFTER description") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo (version) values('4');") or print($mysqli->error);
		
	case 4:
		echo("Updating verdicts table\n");
		$mysqli->query("ALTER TABLE verdicts
					   CHANGE COLUMN result_id result_class_id int") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo (version) values('5');") or print($mysqli->error);
		
	case 5:
		echo("Updating snippets table\n");
		$mysqli->query("ALTER TABLE snippets
					      ADD COLUMN content_code text AFTER content,
					      ADD COLUMN context_code text AFTER context") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo (version) values('6');") or print($mysqli->error);
		
	default:
		echo("Finished updating the schema\n");
}
?>