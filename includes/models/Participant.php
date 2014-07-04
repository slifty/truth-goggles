<?php

require_once(__DIR__."/DBConn.php");
require_once(__DIR__."/FactoryObject.php");
require_once(__DIR__."/JSONObject.php");

class Participant extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $treatment_code;// string
	private $ideology; 		// string
	private $age;	 		// string
	private $gender; 		// string
	private $education; 	// string
	private $ethnicity; 	// string
	private $income; 		// string
	private $dateCreated; 	// timestamp
	
	
	# Caches
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['treatmentCode'] = "";
			$data_array['ideology'] = "";
			$data_array['age'] = "";
			$data_array['gender'] = "";
			$data_array['education'] = "";
			$data_array['ethnicity'] = "";
			$data_array['income'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['treatmentCode'] = "";
			$data_array['ideology'] = "";
			$data_array['age'] = "";
			$data_array['gender'] = "";
			$data_array['education'] = "";
			$data_array['ethnicity'] = "";
			$data_array['income'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT study_participant.id AS itemID,
							   study_participant.treatment_code AS treatmentCode,
							   study_participant.ideology AS ideology,
							   study_participant.age AS age,
							   study_participant.gender AS gender,
							   study_participant.education AS education,
							   study_participant.ethnicity AS ethnicity,
							   study_participant.income AS income,
							   unix_timestamp(study_participant.date_created) as dateCreated
						  FROM study_participant
						 WHERE study_participant.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['treatmentCode'] = $resultArray['treatmentCode'];
			$data_array['ideology'] = $resultArray['ideology'];
			$data_array['age'] = $resultArray['age'];
			$data_array['gender'] = $resultArray['gender'];
			$data_array['education'] = $resultArray['education'];
			$data_array['ethnicity'] = $resultArray['ethnicity'];
			$data_array['income'] = $resultArray['income'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->treatmentCode = isset($data_array["treatmentCode"])?$data_array["treatmentCode"]:"";
		$this->ideology = isset($data_array["ideology"])?$data_array["ideology"]:"";
		$this->age = isset($data_array["age"])?$data_array["age"]:"";
		$this->gender = isset($data_array["gender"])?$data_array["gender"]:"";
		$this->education = isset($data_array["education"])?$data_array["education"]:"";
		$this->income = isset($data_array["income"])?$data_array["income"]:"";
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
			$query_string = "UPDATE study_participant
							   SET study_participant.treatment_code = ".DBConn::clean($this->getTreatmentCode()).",
							       study_participant.ideology = ".DBConn::clean($this->getIdeology()).",
							       study_participant.age = ".DBConn::clean($this->getAge()).",
							       study_participant.gender = ".DBConn::clean($this->getGender()).",
							       study_participant.education = ".DBConn::clean($this->getEducation()).",
							       study_participant.ethnicity = ".DBConn::clean($this->getEthnicity()).",
							       study_participant.income = ".DBConn::clean($this->getIncome())."
							 WHERE study_participant.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO study_participant
								   (study_participant.id,
									study_participant.treatment_code,
									study_participant.ideology,
									study_participant.age,
									study_participant.gender,
									study_participant.education,
									study_participant.ethnicity,
									study_participant.income,
									study_participant.date_created)
							VALUES (0,
									".DBConn::clean($this->getTreatmentCode()).",
									".DBConn::clean($this->getIdeology()).",
									".DBConn::clean($this->getAge()).",
									".DBConn::clean($this->getGender()).",
									".DBConn::clean($this->getEducation()).",
									".DBConn::clean($this->getEthnicity()).",
									".DBConn::clean($this->getIncome()).",
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
		$query_string = "DELETE FROM study_participant
							  WHERE study_participant.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getTreatmentCode() { return $this->treatmentCode; }

	public function getIdeology() { return $this->ideology; }

	public function getAge() { return $this->age; }

	public function getGender() { return $this->gender; }

	public function getEducation() { return $this->education; }

	public function getEthnicity() { return $this->ethnicity; }

	public function getIncome() { return $this->income; }

	public function getDateCreated() { return $this->dateCreated; }
	

	# Setters
	public function setTreatmentCode($str) { $this->treatmentCode = $str; }

	public function setIdeology($str) { $this->ideology = $str; }

	public function setAge($str) { $this->age = $str; }

	public function setGender($str) { $this->gender = $str; }

	public function setEducation($str) { $this->education = $str; }

	public function setEthnicity($str) { $this->ethnicity = $str; }

	public function setIncome($str) { $this->income = $str; }

}

?>