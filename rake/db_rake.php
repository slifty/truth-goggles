<?php
require_once("../conf.php");

// Get connection
$mysqli = DBConn::connect();
if(!$mysqli || $mysqli->connect_error) {
	echo("Could not connect to DB.  Did you follow the install instructions in README?\n");
	die();
}

// Look up installed version
$result = $mysqli->query("select appinfo.version as version
				  			from appinfo");

if(!$result || $result->num_rows == 0)
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
		$mysqli->query("UPDATE appinfo set version ='2';") or print($mysqli->error);
		
	case 2:
		echo("Updating claims table\n");
		$mysqli->query("ALTER TABLE verdicts
						  ADD COLUMN url varchar(255) AFTER claim_source_id") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='3';") or print($mysqli->error);
		
	case 3:
		echo("Updating result_classes table\n");
		$mysqli->query("ALTER TABLE result_classes
					     DROP COLUMN color") or print($mysqli->error);
		$mysqli->query("ALTER TABLE result_classes
						  ADD COLUMN class varchar(32) AFTER description") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='4';") or print($mysqli->error);
		
	case 4:
		echo("Updating verdicts table\n");
		$mysqli->query("ALTER TABLE verdicts
					  CHANGE COLUMN result_id result_class_id int") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='5';") or print($mysqli->error);
		
	case 5:
		echo("Updating snippets table\n");
		$mysqli->query("ALTER TABLE snippets
					      ADD COLUMN content_code text AFTER content,
					      ADD COLUMN context_code text AFTER context") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='6';") or print($mysqli->error);
		
	case 6:
		echo("Renaming claim_sources table to vetting_services \n");
		$mysqli->query("ALTER TABLE claim_sources
			 				 RENAME vetting_services") or print($mysqli->error);
		
		echo("Updating verdicts table\n");
		$mysqli->query("ALTER TABLE verdicts
					  CHANGE COLUMN claim_source_id vetting_service_id int") or print($mysqli->error);
		
		echo("Creating contexts table\n");
		$mysqli->query("CREATE TABLE contexts (id int auto_increment primary key,
											claim_id int,
											url varchar(255),
											verdict_source_id int)") or print($mysqli->error);
		
		echo("Creating arguments table\n");
		$mysqli->query("CREATE TABLE arguments (id int auto_increment primary key,
											claim_id int,
											argument text,
											result_class_id int,
											verdict_source_id int)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='7';") or print($mysqli->error);
		
	case 7:
		echo("Creating hodgepodge table\n");
		$mysqli->query("CREATE TABLE hodgepodge (id int auto_increment primary key,
											claim_id int,
											hodgepodge text,
											date_created datetime)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='8';") or print($mysqli->error);
		
	case 8:
		echo("Renaming hodgepodge table to corpus_items\n");
		$mysqli->query("ALTER TABLE hodgepodge
							 RENAME corpus_items") or print($mysqli->error);
		
		echo("Updating corpus_items table\n");
		$mysqli->query("ALTER TABLE corpus_items
						CHANGE COLUMN hodgepodge content text") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='9';") or print($mysqli->error);
		
	case 9:
		echo("Updating vetting_services table\n");
		$mysqli->query("ALTER TABLE vetting_services
					      ADD COLUMN logo_url text AFTER url") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='10';") or print($mysqli->error);
		
	case 10:
		echo("Updating claims table\n");
		$mysqli->query("ALTER TABLE claims
					      ADD COLUMN date_recorded datetime AFTER content") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='11';") or print($mysqli->error);
		
	case 11:
		echo("Updating verdicts table\n");
		$mysqli->query("ALTER TABLE verdicts
					      ADD COLUMN short_reason text AFTER vetting_service_id,
					      ADD COLUMN long_reason text AFTER short_reason") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='12';") or print($mysqli->error);
	
	case 12:
		echo("Updating all tables to use UTF8\n");
		$mysqli->query("ALTER TABLE appinfo CONVERT TO CHARACTER SET utf8");
		$mysqli->query("ALTER TABLE arguments CONVERT TO CHARACTER SET utf8");
		$mysqli->query("ALTER TABLE claims CONVERT TO CHARACTER SET utf8");
		$mysqli->query("ALTER TABLE contexts CONVERT TO CHARACTER SET utf8");
		$mysqli->query("ALTER TABLE corpus_items CONVERT TO CHARACTER SET utf8");
		$mysqli->query("ALTER TABLE result_classes CONVERT TO CHARACTER SET utf8");
		$mysqli->query("ALTER TABLE snippets CONVERT TO CHARACTER SET utf8");
		$mysqli->query("ALTER TABLE verdicts CONVERT TO CHARACTER SET utf8");
		$mysqli->query("ALTER TABLE vetting_services CONVERT TO CHARACTER SET utf8");
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='13';") or print($mysqli->error);

	case 13:
		echo("Creating tokens table\n");
		$mysqli->query("CREATE TABLE tokens (id int auto_increment primary key,
											snippet_id int,
											content tinytext)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='14';") or print($mysqli->error);

	case 14:
		echo("Dropping old tables\n");
		$mysqli->query("DROP TABLE tokens") or print($mysqli->error);
		$mysqli->query("DROP TABLE arguments") or print($mysqli->error);
		$mysqli->query("DROP TABLE claims") or print($mysqli->error);
		$mysqli->query("DROP TABLE contexts") or print($mysqli->error);
		$mysqli->query("DROP TABLE result_classes") or print($mysqli->error);
		$mysqli->query("DROP TABLE snippets") or print($mysqli->error);
		$mysqli->query("DROP TABLE verdicts") or print($mysqli->error);
		$mysqli->query("DROP TABLE vetting_services") or print($mysqli->error);


		echo("Creating layers table\n");
		$mysqli->query("CREATE TABLE layers (id int auto_increment primary key,
											date_created datetime)") or print($mysqli->error);

		echo("Creating contributions table\n");
		$mysqli->query("CREATE TABLE contributions (id int auto_increment primary key,
											 layer_id int,
											 date_created datetime)") or print($mysqli->error);

		echo("Creating statements table\n");
		$mysqli->query("CREATE TABLE statements (id int auto_increment primary key,
											 contribution_id int,
											 content text,
											 context text,
											 contextCode text,
											 contentCode text,
											 date_created datetime)") or print($mysqli->error);

		echo("Creating evidence table\n");
		$mysqli->query("CREATE TABLE evidence (id int auto_increment primary key,
											 contribution_id int,
											 file_path text,
											 date_created datetime)") or print($mysqli->error);

		echo("Creating arguments table\n");
		$mysqli->query("CREATE TABLE arguments (id int auto_increment primary key,
											 contribution_id int,
											 summary text,
											 content text,
											 date_created datetime)") or print($mysqli->error);

		echo("Creating questions table\n");
		$mysqli->query("CREATE TABLE questions (id int auto_increment primary key,
											 contribution_id int,
											 content text,
											 date_created datetime)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='15';") or print($mysqli->error);


	case 15:
		echo("Creating study_participant table\n");
		$mysqli->query("CREATE TABLE study_participant (id int auto_increment primary key,
											treatment_code text,
											ideology text,
											age text,
											gender text,
											education text,
											ethnicity text,
											income text,
											date_created datetime)") or print($mysqli->error);

		echo("Creating study_survey table\n");
		$mysqli->query("CREATE TABLE study_survey (id int auto_increment primary key,
											participant_id int,
											treatment_id int,
											story_believable text,
											story_thorough text,
											story_accurate text,
											story_factual text,
											story_biased text,
											story_interesting text,
											story_informative text,
											story_important text,
											story_serious text,
											story_good text,
											story_positive text,
											story_quality text,
											journalist_believable text,
											journalist_thorough text,
											journalist_accurate text,
											journalist_factual text,
											journalist_biased text,
											journalist_good text,
											journalist_professional text,
											journalist_careless text,
											article_positive_2 text,
											article_positive_pct text,
											article_lean text,
											feel_angry text,
											feel_irritated text,
											feel_aggravated text,
											feel_mad text,
											feel_fearful text,
											feel_afraid text,
											feel_scared text,
											feel_upset text,
											feel_elated text,
											feel_happy text,
											feel_joyful text,
											feel_cheerful text,
											feel_sad text,
											feel_dreary text,
											feel_dismal text,
											recall text,
											feedback text,
											date_created datetime)") or print($mysqli->error);
		echo("Creating study_event table\n");
		$mysqli->query("CREATE TABLE study_event (id int auto_increment primary key,
											participant_id int,
											type text,
											contribution_id int,
											date_created datetime)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='14';") or print($mysqli->error);

	default:
		echo("Finished updating the schema\n");
}
?>