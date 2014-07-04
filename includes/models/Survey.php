<?php

require_once(__DIR__."/DBConn.php");
require_once(__DIR__."/FactoryObject.php");
require_once(__DIR__."/JSONObject.php");

class Survey extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $participantID; 	// int
	private $treatmentID; 		// int
	private $storyBelievable; 	// string
	private $storyThorough;	 	// string
	private $storyAccurate; 	// string
	private $storyFactual; 		// string
	private $storyBiased; 		// string
	private $storyInteresting; 	// string
	private $storyInformative; 	// string
	private $storyImportant; 	// string
	private $storySerious; 		// string
	private $storyGood;		 	// string
	private $storyPositive; 	// string
	private $storyQuality;	 	// string
	private $journalistBelievable; 	// string
	private $journalistThorough; 	// string
	private $journalistAccurate; 	// string
	private $journalistFactual; 		// string
	private $journalistBiased;		 	// string
	private $journalistGood; 	// string
	private $journalistProfessional;	// string
	private $journalistCareless;	// string
	private $articlePositive2;	// string
	private $articlePositivePct;	// string
	private $articleLean;	// string
	private $feelAngry;	// string
	private $feelIrritated;	// string
	private $feelAggravated;	// string
	private $feelMad;	// string
	private $feelFearful;	// string
	private $feelAfraid;	// string
	private $feelScared;	// string
	private $feelUpset;	// string
	private $feelElated;	// string
	private $feelHappy;	// string
	private $feelJoyful;	// string
	private $feelCheerful;	// string
	private $feelSad;	// string
	private $feelDreary;	// string
	private $feelDismal;	// string
	private $recall;	// string
	private $feedback;	// string
	private $dateCreated; 	// timestamp
	
	
	# Caches
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['participantID'] = 0;
			$data_array['treatmentID'] = 0;
			$data_array['storyBelievable'] = "";
			$data_array['storyThorough'] = "";
			$data_array['storyAccurate'] = "";
			$data_array['storyFactual'] = "";
			$data_array['storyBiased'] = "";
			$data_array['storyInteresting'] = "";
			$data_array['storyInformative'] = "";
			$data_array['storyImportant'] = "";
			$data_array['storySerious'] = "";
			$data_array['storyGood'] = "";
			$data_array['storyPositive'] = "";
			$data_array['storyQuality'] = "";
			$data_array['journalistBelievable'] = "";
			$data_array['journalistAccurate'] = "";
			$data_array['journalistFactual'] = "";
			$data_array['journalistBiased'] = "";
			$data_array['journalistGood'] = "";
			$data_array['journalistProfessional'] = "";
			$data_array['journalistCareless'] = "";
			$data_array['articlePositive2'] = "";
			$data_array['articlePositivePct'] = "";
			$data_array['articleLean'] = "";
			$data_array['feelAngry'] = "";
			$data_array['feelIrritated'] = "";
			$data_array['feelAggravated'] = "";
			$data_array['feelMad'] = "";
			$data_array['feelFearful'] = "";
			$data_array['feelScared'] = "";
			$data_array['feelUpset'] = "";
			$data_array['feelElated'] = "";
			$data_array['feelHappy'] = "";
			$data_array['feelJoyful'] = "";
			$data_array['feelCheerful'] = "";
			$data_array['feelSad'] = "";
			$data_array['feelDreary'] = "";
			$data_array['feelDismal'] = "";
			$data_array['recall'] = "";
			$data_array['feedback'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['participantID'] = 0;
			$data_array['treatmentID'] = 0;
			$data_array['storyBelievable'] = "";
			$data_array['storyThorough'] = "";
			$data_array['storyAccurate'] = "";
			$data_array['storyFactual'] = "";
			$data_array['storyBiased'] = "";
			$data_array['storyInteresting'] = "";
			$data_array['storyInformative'] = "";
			$data_array['storyImportant'] = "";
			$data_array['storySerious'] = "";
			$data_array['storyGood'] = "";
			$data_array['storyPositive'] = "";
			$data_array['storyQuality'] = "";
			$data_array['journalistBelievable'] = "";
			$data_array['journalistAccurate'] = "";
			$data_array['journalistFactual'] = "";
			$data_array['journalistBiased'] = "";
			$data_array['journalistGood'] = "";
			$data_array['journalistProfessional'] = "";
			$data_array['journalistCareless'] = "";
			$data_array['articlePositive2'] = "";
			$data_array['articlePositivePct'] = "";
			$data_array['articleLean'] = "";
			$data_array['feelAngry'] = "";
			$data_array['feelIrritated'] = "";
			$data_array['feelAggravated'] = "";
			$data_array['feelMad'] = "";
			$data_array['feelFearful'] = "";
			$data_array['feelScared'] = "";
			$data_array['feelUpset'] = "";
			$data_array['feelElated'] = "";
			$data_array['feelHappy'] = "";
			$data_array['feelJoyful'] = "";
			$data_array['feelCheerful'] = "";
			$data_array['feelSad'] = "";
			$data_array['feelDreary'] = "";
			$data_array['feelDismal'] = "";
			$data_array['recall'] = "";
			$data_array['feedback'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT study_survey.id AS itemID,
								study_survey.participant_id AS participantID,
								study_survey.treatment_id AS treatmentID,
								study_survey.story_believable AS storyBelievable,
								study_survey.story_thorough AS storyThorough,
								study_survey.story_accurate AS storyAccurate,
								study_survey.story_factual AS storyFactual,
								study_survey.story_biased AS storyBiased,
								study_survey.story_interesting AS storyInteresting,
								study_survey.story_informative AS storyInformative,
								study_survey.story_important AS storyImportant,
								study_survey.story_serious AS storySerious,
								study_survey.story_good AS storyGood,
								study_survey.story_positive AS storyPositive,
								study_survey.story_quality AS storyQuality,
								study_survey.journalist_believable AS journalistBelievable,
								study_survey.journalist_thorough AS journalistThorough,
								study_survey.journalist_accurate AS journalistAccurate,
								study_survey.journalist_factual AS journalistFactual,
								study_survey.journalist_biased AS journalistBiased,
								study_survey.journalist_good AS journalistGood,
								study_survey.journalist_professional AS journalistProfessional,
								study_survey.journalist_careless AS journalistCareless,
								study_survey.article_positive_2 AS articlePositive2,
								study_survey.article_positive_pct AS articlePositivePct,
								study_survey.article_lean AS articleLean,
								study_survey.feel_angry AS feelAngry,
								study_survey.feel_irritated AS feelIrritated,
								study_survey.feel_aggravated AS feelAggravated,
								study_survey.feel_mad AS feelMad,
								study_survey.feel_fearful AS feelFearful,
								study_survey.feel_afraid AS feelAfraid,
								study_survey.feel_scared AS feelScared,
								study_survey.feel_upset AS feelUpset,
								study_survey.feel_elated AS feelElated,
								study_survey.feel_happy AS feelHappy,
								study_survey.feel_joyful AS feelJoyful,
								study_survey.feel_cheerful AS feelCheerful,
								study_survey.feel_sad AS feelSad,
								study_survey.feel_dreary AS feelDreary,
								study_survey.feel_dismal AS feelDismal,
								study_survey.recall AS recall,
								study_survey.feedback AS feedback,
							   unix_timestamp(study_survey.date_created) as dateCreated
						  FROM study_survey
						 WHERE study_survey.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['participantID'] = $resultArray['participantID'];
			$data_array['treatmentID'] = $resultArray['treatmentID'];
			$data_array['storyBelievable'] = $resultArray['storyBelievable'];
			$data_array['storyThorough'] = $resultArray['storyThorough'];
			$data_array['storyAccurate'] = $resultArray['storyAccurate'];
			$data_array['storyFactual'] = $resultArray['storyFactual'];
			$data_array['storyBiased'] = $resultArray['storyBiased'];
			$data_array['storyInteresting'] = $resultArray['storyInteresting'];
			$data_array['storyInformative'] = $resultArray['storyInformative'];
			$data_array['storyImportant'] = $resultArray['storyImportant'];
			$data_array['storySerious'] = $resultArray['storySerious'];
			$data_array['storyGood'] = $resultArray['storyGood'];
			$data_array['storyPositive'] = $resultArray['storyPositive'];
			$data_array['storyQuality'] = $resultArray['storyQuality'];
			$data_array['journalistBelievable'] = $resultArray['journalistBelievable'];
			$data_array['journalistThorough'] = $resultArray['journalistThorough'];
			$data_array['journalistAccurate'] = $resultArray['journalistAccurate'];
			$data_array['journalistFactual'] = $resultArray['journalistFactual'];
			$data_array['journalistBiased'] = $resultArray['journalistBiased'];
			$data_array['journalistGood'] = $resultArray['journalistGood'];
			$data_array['journalistProfessional'] = $resultArray['journalistProfessional'];
			$data_array['journalistCareless'] = $resultArray['journalistCareless'];
			$data_array['articlePositive2'] = $resultArray['articlePositive2'];
			$data_array['articlePositivePct'] = $resultArray['articlePositivePct'];
			$data_array['articleLean'] = $resultArray['articleLean'];
			$data_array['feelAngry'] = $resultArray['feelAngry'];
			$data_array['feelIrritated'] = $resultArray['feelIrritated'];
			$data_array['feelAggravated'] = $resultArray['feelAggravated'];
			$data_array['feelMad'] = $resultArray['feelMad'];
			$data_array['feelFearful'] = $resultArray['feelFearful'];
			$data_array['feelAfraid'] = $resultArray['feelAfraid'];
			$data_array['feelScared'] = $resultArray['feelScared'];
			$data_array['feelUpset'] = $resultArray['feelUpset'];
			$data_array['feelElated'] = $resultArray['feelElated'];
			$data_array['feelHappy'] = $resultArray['feelHappy'];
			$data_array['feelJoyful'] = $resultArray['feelJoyful'];
			$data_array['feelCheerful'] = $resultArray['feelCheerful'];
			$data_array['feelSad'] = $resultArray['feelSad'];
			$data_array['feelDreary'] = $resultArray['feelDreary'];
			$data_array['feelDismal'] = $resultArray['feelDismal'];
			$data_array['recall'] = $resultArray['recall'];
			$data_array['feedback'] = $resultArray['feedback'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->participantID = isset($data_array["participantID"])?$data_array["participantID"]:"";
		$this->treatmentID = isset($data_array["treatmentID"])?$data_array["treatmentID"]:"";
		$this->storyBelievable = isset($data_array["storyBelievable"])?$data_array["storyBelievable"]:"";
		$this->storyThorough = isset($data_array["storyThorough"])?$data_array["storyThorough"]:"";
		$this->storyAccurate = isset($data_array["storyAccurate"])?$data_array["storyAccurate"]:"";
		$this->storyFactual = isset($data_array["storyFactual"])?$data_array["storyFactual"]:"";
		$this->storyBiased = isset($data_array["storyBiased"])?$data_array["storyBiased"]:"";
		$this->storyInteresting = isset($data_array["storyInteresting"])?$data_array["storyInteresting"]:"";
		$this->storyInformative = isset($data_array["storyInformative"])?$data_array["storyInformative"]:"";
		$this->storyImportant = isset($data_array["storyImportant"])?$data_array["storyImportant"]:"";
		$this->storySerious = isset($data_array["storySerious"])?$data_array["storySerious"]:"";
		$this->storyGood = isset($data_array["storyGood"])?$data_array["storyGood"]:"";
		$this->storyPositive = isset($data_array["storyPositive"])?$data_array["storyPositive"]:"";
		$this->storyQuality = isset($data_array["storyQuality"])?$data_array["storyQuality"]:"";
		$this->journalistBelievable = isset($data_array["journalistBelievable"])?$data_array["journalistBelievable"]:"";
		$this->journalistThorough = isset($data_array["journalistThorough"])?$data_array["journalistThorough"]:"";
		$this->journalistAccurate = isset($data_array["journalistAccurate"])?$data_array["journalistAccurate"]:"";
		$this->journalistFactual = isset($data_array["journalistFactual"])?$data_array["journalistFactual"]:"";
		$this->journalistBiased = isset($data_array["journalistBiased"])?$data_array["journalistBiased"]:"";
		$this->journalistGood = isset($data_array["journalistGood"])?$data_array["journalistGood"]:"";
		$this->journalistProfessional = isset($data_array["journalistProfessional"])?$data_array["journalistProfessional"]:"";
		$this->journalistCareless = isset($data_array["journalistCareless"])?$data_array["journalistCareless"]:"";
		$this->articlePositive2 = isset($data_array["articlePositive2"])?$data_array["articlePositive2"]:"";
		$this->articlePositivePct = isset($data_array["articlePositivePct"])?$data_array["articlePositivePct"]:"";
		$this->articleLean = isset($data_array["articleLean"])?$data_array["articleLean"]:"";
		$this->feelAngry = isset($data_array["feelAngry"])?$data_array["feelAngry"]:"";
		$this->feelIrritated = isset($data_array["feelIrritated"])?$data_array["feelIrritated"]:"";
		$this->feelAggravated = isset($data_array["feelAggravated"])?$data_array["feelAggravated"]:"";
		$this->feelMad = isset($data_array["feelMad"])?$data_array["feelMad"]:"";
		$this->feelFearful = isset($data_array["feelFearful"])?$data_array["feelFearful"]:"";
		$this->feelAfraid = isset($data_array["feelAfraid"])?$data_array["feelAfraid"]:"";
		$this->feelScared = isset($data_array["feelScared"])?$data_array["feelScared"]:"";
		$this->feelUpset = isset($data_array["feelUpset"])?$data_array["feelUpset"]:"";
		$this->feelElated = isset($data_array["feelElated"])?$data_array["feelElated"]:"";
		$this->feelHappy = isset($data_array["feelHappy"])?$data_array["feelHappy"]:"";
		$this->feelJoyful = isset($data_array["feelJoyful"])?$data_array["feelJoyful"]:"";
		$this->feelCheerful = isset($data_array["feelCheerful"])?$data_array["feelCheerful"]:"";
		$this->feelSad = isset($data_array["feelSad"])?$data_array["feelSad"]:"";
		$this->feelDreary = isset($data_array["feelDreary"])?$data_array["feelDreary"]:"";
		$this->feelDismal = isset($data_array["feelDismal"])?$data_array["feelDismal"]:"";
		$this->recall = isset($data_array["recall"])?$data_array["recall"]:"";
		$this->feedback = isset($data_array["feedback"])?$data_array["feedback"]:"";
		$this->dateCreated = isset($data_array["dateCreated"])?$data_array["dateCreated"]:0;
	}
	
	
	# JSONObject Methods
	public function toJSON($contentStart=null, $contentLength=null) {
		$json = '';
		return $json;
	}
	
	
	# Data Methods
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate()) return;
		
		$mysqli = DBConn::connect();
		
		if($this->isUpdate()) {
			// Update an existing record
			$query_string = "UPDATE study_survey
							   SET study_survey.participant_id = ".DBConn::clean($this->getParticipantID()).",
							   		study_survey.treatment_id = ".DBConn::clean($this->getTreatmentID()).",
							   		study_survey.story_believable = ".DBConn::clean($this->getStoryBelievable()).",
									study_survey.story_thorough = ".DBConn::clean($this->getStoryThorough()).",
									study_survey.story_accurate = ".DBConn::clean($this->getStoryAccurate()).",
									study_survey.story_factual = ".DBConn::clean($this->getStoryFactual()).",
									study_survey.story_biased = ".DBConn::clean($this->getStoryBiased()).",
									study_survey.story_interesting = ".DBConn::clean($this->getStoryInteresting()).",
									study_survey.story_informative = ".DBConn::clean($this->getStoryInformative()).",
									study_survey.story_important = ".DBConn::clean($this->getStoryImportant()).",
									study_survey.story_serious = ".DBConn::clean($this->getStorySerious()).",
									study_survey.story_good = ".DBConn::clean($this->getStoryGood()).",
									study_survey.story_positive = ".DBConn::clean($this->getStoryPositive()).",
									study_survey.story_quality = ".DBConn::clean($this->getStoryQuality()).",
									study_survey.journalist_believable = ".DBConn::clean($this->getJournalistBelievable()).",
									study_survey.journalist_thorough = ".DBConn::clean($this->getJournalistThorough()).",
									study_survey.journalist_accurate = ".DBConn::clean($this->getJournalistAccurate()).",
									study_survey.journalist_factual = ".DBConn::clean($this->getJournalistFactual()).",
									study_survey.journalist_biased = ".DBConn::clean($this->getJournalistBiased()).",
									study_survey.journalist_good = ".DBConn::clean($this->getJournalistGood()).",
									study_survey.journalist_professional = ".DBConn::clean($this->getJournalistProfessional()).",
									study_survey.journalist_careless = ".DBConn::clean($this->getJournalistCareless()).",
									study_survey.article_positive_2 = ".DBConn::clean($this->getArticlePositive2()).",
									study_survey.article_positive_pct = ".DBConn::clean($this->getArticlePositivePct()).",
									study_survey.article_lean = ".DBConn::clean($this->getArticleLean()).",
									study_survey.feel_angry = ".DBConn::clean($this->getFeelAngry()).",
									study_survey.feel_irritated = ".DBConn::clean($this->getFeelIrritated()).",
									study_survey.feel_aggravated = ".DBConn::clean($this->getFeelAggravated()).",
									study_survey.feel_mad = ".DBConn::clean($this->getFeelMad()).",
									study_survey.feel_fearful = ".DBConn::clean($this->getFeelFearful()).",
									study_survey.feel_afraid = ".DBConn::clean($this->getFeelAfraid()).",
									study_survey.feel_scared = ".DBConn::clean($this->getFeelScared()).",
									study_survey.feel_upset = ".DBConn::clean($this->getFeelUpset()).",
									study_survey.feel_elated = ".DBConn::clean($this->getFeelElated()).",
									study_survey.feel_happy = ".DBConn::clean($this->getFeelHappy()).",
									study_survey.feel_joyful = ".DBConn::clean($this->getFeelJoyful()).",
									study_survey.feel_cheerful = ".DBConn::clean($this->getFeelCheerful()).",
									study_survey.feel_sad = ".DBConn::clean($this->getFeelSad()).",
									study_survey.feel_dreary = ".DBConn::clean($this->getFeelDreary()).",
									study_survey.feel_dismal = ".DBConn::clean($this->getFeelDismal()).",
									study_survey.recall = ".DBConn::clean($this->getRecall()).",
									study_survey.feedback = ".DBConn::clean($this->getFeedback())."
							 WHERE study_survey.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO study_survey
								   (study_survey.id,
									study_survey.participant_id,
									study_survey.treatment_id,
									study_survey.story_believable,
									study_survey.story_thorough,
									study_survey.story_accurate,
									study_survey.story_factual,
									study_survey.story_biased,
									study_survey.story_interesting,
									study_survey.story_informative,
									study_survey.story_important,
									study_survey.story_serious,
									study_survey.story_good,
									study_survey.story_positive,
									study_survey.story_quality,
									study_survey.journalist_believable,
									study_survey.journalist_thorough,
									study_survey.journalist_accurate,
									study_survey.journalist_factual,
									study_survey.journalist_biased,
									study_survey.journalist_good,
									study_survey.journalist_professional,
									study_survey.journalist_careless,
									study_survey.article_positive_2,
									study_survey.article_positive_pct,
									study_survey.article_lean,
									study_survey.feel_angry,
									study_survey.feel_irritated,
									study_survey.feel_aggravated,
									study_survey.feel_mad,
									study_survey.feel_fearful,
									study_survey.feel_afraid,
									study_survey.feel_scared,
									study_survey.feel_upset,
									study_survey.feel_elated,
									study_survey.feel_happy,
									study_survey.feel_joyful,
									study_survey.feel_cheerful,
									study_survey.feel_sad,
									study_survey.feel_dreary,
									study_survey.feel_dismal,
									study_survey.recall,
									study_survey.feedback,
									study_survey.date_created)
							VALUES (0,
									".DBConn::clean($this->getParticipantID()).",
									".DBConn::clean($this->getTreatmentID()).",
									".DBConn::clean($this->getStoryBelievable()).",
									".DBConn::clean($this->getStoryThorough()).",
									".DBConn::clean($this->getStoryAccurate()).",
									".DBConn::clean($this->getStoryFactual()).",
									".DBConn::clean($this->getStoryBiased()).",
									".DBConn::clean($this->getStoryInteresting()).",
									".DBConn::clean($this->getStoryInformative()).",
									".DBConn::clean($this->getStoryImportant()).",
									".DBConn::clean($this->getStorySerious()).",
									".DBConn::clean($this->getStoryGood()).",
									".DBConn::clean($this->getStoryPositive()).",
									".DBConn::clean($this->getStoryQuality()).",
									".DBConn::clean($this->getJournalistBelievable()).",
									".DBConn::clean($this->getJournalistThorough()).",
									".DBConn::clean($this->getJournalistAccurate()).",
									".DBConn::clean($this->getJournalistFactual()).",
									".DBConn::clean($this->getJournalistBiased()).",
									".DBConn::clean($this->getJournalistGood()).",
									".DBConn::clean($this->getJournalistProfessional()).",
									".DBConn::clean($this->getJournalistCareless()).",
									".DBConn::clean($this->getArticlePositive2()).",
									".DBConn::clean($this->getArticlePositivePct()).",
									".DBConn::clean($this->getArticleLean()).",
									".DBConn::clean($this->getFeelAngry()).",
									".DBConn::clean($this->getFeelIrritated()).",
									".DBConn::clean($this->getFeelAggravated()).",
									".DBConn::clean($this->getFeelMad()).",
									".DBConn::clean($this->getFeelFearful()).",
									".DBConn::clean($this->getFeelAfraid()).",
									".DBConn::clean($this->getFeelScared()).",
									".DBConn::clean($this->getFeelUpset()).",
									".DBConn::clean($this->getFeelElated()).",
									".DBConn::clean($this->getFeelHappy()).",
									".DBConn::clean($this->getFeelJoyful()).",
									".DBConn::clean($this->getFeelCheerful()).",
									".DBConn::clean($this->getFeelSad()).",
									".DBConn::clean($this->getFeelDreary()).",
									".DBConn::clean($this->getFeelDismal()).",
									".DBConn::clean($this->getRecall()).",
									".DBConn::clean($this->getFeedback()).",
									NOW())";
			
			$mysqli->query($query_string) or print($mysqli->error);
			$this->setItemID($mysqli->insert_id);
		}
		
		// Parent Operations
		return parent::save();
	}
	
	public function delete() {
		parent::delete();
		$mysqli = DBConn::connect();
		
		// Delete this record
		$query_string = "DELETE FROM study_survey
							  WHERE study_survey.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getParticipantID() { return $this->participantID; }

	public function getTreatmentID() { return $this->treatmentID; }

	public function getStoryBelievable() { return $this->storyBelievable; }
	public function getStoryThorough() { return $this->storyThorough; }
	public function getStoryAccurate() { return $this->storyAccurate; }
	public function getStoryFactual() { return $this->storyFactual; }
	public function getStoryBiased() { return $this->storyBiased; }
	public function getStoryInteresting() { return $this->storyInteresting; }
	public function getStoryInformative() { return $this->storyInformative; }
	public function getStoryImportant() { return $this->storyImportant; }
	public function getStorySerious() { return $this->storySerious; }
	public function getStoryGood() { return $this->storyGood; }
	public function getStoryPositive() { return $this->storyPositive; }
	public function getStoryQuality() { return $this->storyQuality; }
	public function getJournalistBelievable() { return $this->journalistBelievable; }
	public function getJournalistThorough() { return $this->journalistThorough; }
	public function getJournalistAccurate() { return $this->journalistAccurate; }
	public function getJournalistFactual() { return $this->journalistFactual; }
	public function getJournalistBiased() { return $this->journalistBiased; }
	public function getJournalistGood() { return $this->journalistGood; }
	public function getJournalistProfessional() { return $this->journalistProfessional; }
	public function getJournalistCareless() { return $this->journalistCareless; }
	public function getArticlePositive2() { return $this->articlePositive2; }
	public function getArticlePositivePct() { return $this->articlePositivePct; }
	public function getArticleLean() { return $this->articleLean; }
	public function getFeelAngry() { return $this->feelAngry; }
	public function getFeelIrritated() { return $this->feelIrritated; }
	public function getFeelAggravated() { return $this->feelAggravated; }
	public function getFeelMad() { return $this->feelMad; }
	public function getFeelFearful() { return $this->feelFearful; }
	public function getFeelAfraid() { return $this->feelAfraid; }
	public function getFeelScared() { return $this->feelScared; }
	public function getFeelUpset() { return $this->feelUpset; }
	public function getFeelElated() { return $this->feelElated; }
	public function getFeelHappy() { return $this->feelHappy; }
	public function getFeelJoyful() { return $this->feelJoyful; }
	public function getFeelCheerful() { return $this->feelCheerful; }
	public function getFeelSad() { return $this->feelSad; }
	public function getFeelDreary() { return $this->feelDreary; }
	public function getFeelDismal() { return $this->feelDismal; }
	public function getRecall() { return $this->recall; }
	public function getFeedback() { return $this->feedback; }
	public function getDateCreated() { return $this->dateCreated; }
	

	# Setters
	public function setParticipantID($int) { $this->participantID = $int; }
	public function setTreatmentID($int) { $this->treatmentID = $int; }

	public function setStoryBelievable($str) { $this->storyBelievable = $str; }
	public function setStoryThorough($str) { $this->storyThorough = $str; }
	public function setStoryAccurate($str) { $this->storyAccurate = $str; }
	public function setStoryFactual($str) { $this->storyFactual = $str; }
	public function setStoryBiased($str) { $this->storyBiased = $str; }
	public function setStoryInteresting($str) { $this->storyInteresting = $str; }
	public function setStoryInformative($str) { $this->storyInformative = $str; }
	public function setStoryImportant($str) { $this->storyImportant = $str; }
	public function setStorySerious($str) { $this->storySerious = $str; }
	public function setStoryGood($str) { $this->storyGood = $str; }
	public function setStoryPositive($str) { $this->storyPositive = $str; }
	public function setStoryQuality($str) { $this->storyQuality = $str; }
	public function setJournalistBelievable($str) { $this->journalistBelievable = $str; }
	public function setJournalistThorough($str) { $this->journalistThorough = $str; }
	public function setJournalistAccurate($str) { $this->journalistAccurate = $str; }
	public function setJournalistFactual($str) { $this->journalistFactual = $str; }
	public function setJournalistBiased($str) { $this->journalistBiased = $str; }
	public function setJournalistGood($str) { $this->journalistGood = $str; }
	public function setJournalistProfessional($str) { $this->journalistProfessional = $str; }
	public function setJournalistCareless($str) { $this->journalistCareless = $str; }
	public function setArticlePositive2($str) { $this->articlePositive2 = $str; }
	public function setArticlePositivePct($str) { $this->articlePositivePct = $str; }
	public function setArticleLean($str) { $this->articleLean = $str; }
	public function setFeelAngry($str) { $this->feelAngry = $str; }
	public function setFeelIrritated($str) { $this->feelIrritated = $str; }
	public function setFeelAggravated($str) { $this->feelAggravated = $str; }
	public function setFeelMad($str) { $this->feelMad = $str; }
	public function setFeelFearful($str) { $this->feelFearful = $str; }
	public function setFeelAfraid($str) { $this->feelAfraid = $str; }
	public function setFeelScared($str) { $this->feelScared = $str; }
	public function setFeelUpset($str) { $this->feelUpset = $str; }
	public function setFeelElated($str) { $this->feelElated = $str; }
	public function setFeelHappy($str) { $this->feelHappy = $str; }
	public function setFeelJoyful($str) { $this->feelJoyful = $str; }
	public function setFeelCheerful($str) { $this->feelCheerful = $str; }
	public function setFeelSad($str) { $this->feelSad = $str; }
	public function setFeelDreary($str) { $this->feelDreary = $str; }
	public function setFeelDismal($str) { $this->feelDismal = $str; }
	public function setRecall($str) { $this->recall = $str; }
	public function setFeedback($str) { $this->feedback = $str; }

}

?>