<?php
	session_start();
	$participant = Participant::getObject($_SESSION['participant_id']);

	$next = array_pop($_SESSION['order']);
	if(array_key_exists("current", $_SESSION))
		$_SESSION["previous"] = $_SESSION["current"];
	else
		$_SESSION["previous"] = null;
	$_SESSION["current"] = $next;

	if($_SESSION["previous"] == "survey") {
		$survey = new Survey();
		$survey->setParticipantID($participant->getItemID());
		$survey->setTreatmentID(array_key_exists("treatment-id", $_POST)?$_POST['treatment-id']:0);
		$survey->setStoryBelievable(array_key_exists("story-believable", $_POST)?$_POST['story-believable']:"");
		$survey->setStoryThorough(array_key_exists("story-thorough", $_POST)?$_POST['story-thorough']:"");
		$survey->setStoryAccurate(array_key_exists("story-accurate", $_POST)?$_POST['story-accurate']:"");
		$survey->setStoryFactual(array_key_exists("story-factual", $_POST)?$_POST['story-factual']:"");
		$survey->setStoryBiased(array_key_exists("story-biased", $_POST)?$_POST['story-biased']:"");
		$survey->setStoryInteresting(array_key_exists("story-interesting", $_POST)?$_POST['story-interesting']:"");
		$survey->setStoryInformative(array_key_exists("story-informative", $_POST)?$_POST['story-informative']:"");
		$survey->setStoryImportant(array_key_exists("story-important", $_POST)?$_POST['story-important']:"");
		$survey->setStorySerious(array_key_exists("story-serious", $_POST)?$_POST['story-serious']:"");
		$survey->setStoryGood(array_key_exists("story-good", $_POST)?$_POST['story-good']:"");
		$survey->setStoryPositive(array_key_exists("story-positive", $_POST)?$_POST['story-positive']:"");
		$survey->setStoryQuality(array_key_exists("story-quality", $_POST)?$_POST['story-quality']:"");
		$survey->setJournalistBelievable(array_key_exists("journalist-believable", $_POST)?$_POST['journalist-believable']:"");
		$survey->setJournalistThorough(array_key_exists("journalist-thorough", $_POST)?$_POST['journalist-thorough']:"");
		$survey->setJournalistAccurate(array_key_exists("journalist-accurate", $_POST)?$_POST['journalist-accurate']:"");
		$survey->setJournalistFactual(array_key_exists("journalist-factual", $_POST)?$_POST['journalist-factual']:"");
		$survey->setJournalistBiased(array_key_exists("journalist-biased", $_POST)?$_POST['journalist-biased']:"");
		$survey->setJournalistGood(array_key_exists("journalist-good", $_POST)?$_POST['journalist-good']:"");
		$survey->setJournalistProfessional(array_key_exists("journalist-professional", $_POST)?$_POST['journalist-professional']:"");
		$survey->setJournalistCareless(array_key_exists("journalist-careless", $_POST)?$_POST['journalist-careless']:"");
		$survey->setArticlePositive2(array_key_exists("article-positive-2", $_POST)?$_POST['article-positive-2']:"");
		$survey->setArticlePositivePct(array_key_exists("article-positive-pct", $_POST)?$_POST['article-positive-pct']:"");
		$survey->setArticleLean(array_key_exists("article-lean", $_POST)?$_POST['article-lean']:"");
		$survey->setFeelAngry(array_key_exists("feel-angry", $_POST)?$_POST['feel-angry']:"");
		$survey->setFeelIrritated(array_key_exists("feel-irritated", $_POST)?$_POST['feel-irritated']:"");
		$survey->setFeelAggravated(array_key_exists("feel-aggravated", $_POST)?$_POST['feel-aggravated']:"");
		$survey->setFeelMad(array_key_exists("feel-mad", $_POST)?$_POST['feel-mad']:"");
		$survey->setFeelFearful(array_key_exists("feel-fearful", $_POST)?$_POST['feel-fearful']:"");
		$survey->setFeelAfraid(array_key_exists("feel-afraid", $_POST)?$_POST['feel-afraid']:"");
		$survey->setFeelScared(array_key_exists("feel-scared", $_POST)?$_POST['feel-scared']:"");
		$survey->setFeelUpset(array_key_exists("feel-upset", $_POST)?$_POST['feel-upset']:"");
		$survey->setFeelElated(array_key_exists("feel-elated", $_POST)?$_POST['feel-elated']:"");
		$survey->setFeelHappy(array_key_exists("feel-happy", $_POST)?$_POST['feel-happy']:"");
		$survey->setFeelJoyful(array_key_exists("feel-joyful", $_POST)?$_POST['feel-joyful']:"");
		$survey->setFeelCheerful(array_key_exists("feel-cheerful", $_POST)?$_POST['feel-cheerful']:"");
		$survey->setFeelSad(array_key_exists("feel-sad", $_POST)?$_POST['feel-sad']:"");
		$survey->setFeelDreary(array_key_exists("feel-dreary", $_POST)?$_POST['feel-dreary']:"");
		$survey->setFeelDismal(array_key_exists("feel-dismal", $_POST)?$_POST['feel-dismal']:"");
		$survey->setRecall(array_key_exists("recall", $_POST)?$_POST['recall']:"");
		$survey->setFeedback(array_key_exists("feedback", $_POST)?$_POST['feedback']:"");
		$survey->save();
	}

	if($_SESSION["previous"] == "exit") {
		$participant->setIdeology(array_key_exists("ideology",$_POST)?$_POST['ideology']:"");
		$participant->setAge(array_key_exists("age",$_POST)?$_POST['age']:"");
		$participant->setGender(array_key_exists("gender",$_POST)?$_POST['gender']:"");
		$participant->setEducation(array_key_exists("education",$_POST)?$_POST['education']:"");
		$participant->setEthnicity(array_key_exists("ethnicity",$_POST)?$_POST['ethnicity']:"");
		$participant->setIncome(array_key_exists("income",$_POST)?$_POST['income']:"");
		$participant->save();
	}

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
		case "exit":
			$target .= "study/exit";
			break;
		case "survey":
			$target .= "study/survey";
			break;
		default:
			$target .= "study/thanks";
	}
	header( 'Location: '.$target ) ;

?>