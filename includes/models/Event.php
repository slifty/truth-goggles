<?php

require_once(__DIR__."/DBConn.php");
require_once(__DIR__."/FactoryObject.php");
require_once(__DIR__."/JSONObject.php");

class Event extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $participantID;// int
	private $type; 		// string
	private $contributionID; // int
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
			$data_array['type'] = "";
			$data_array['contributionID'] = 0;
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['participantID'] = 0;
			$data_array['type'] = "";
			$data_array['contributionID'] = 0;
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT study_event.id AS itemID,
							   study_event.participant_id AS participantID,
							   study_event.type AS type,
							   study_event.contribution_id AS contributionID,
							   unix_timestamp(study_event.date_created) as dateCreated
						  FROM study_event
						 WHERE study_event.id IN (".$objectString.")";
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
			$data_array['type'] = $resultArray['type'];
			$data_array['contributionID'] = $resultArray['contributionID'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->participantID = isset($data_array["participantID"])?$data_array["participantID"]:0;
		$this->type = isset($data_array["type"])?$data_array["type"]:"";
		$this->contributionID = isset($data_array["contributionID"])?$data_array["contributionID"]:0;
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
			$query_string = "UPDATE study_event
							   SET study_event.participant_id = ".DBConn::clean($this->getParticipantID()).",
							       study_event.type = ".DBConn::clean($this->getType()).",
							       study_event.contribution_id = ".DBConn::clean($this->getContributionID())."
							 WHERE study_event.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO study_event
								   (study_event.id,
									study_event.participant_id,
									study_event.type,
									study_event.contribution_id,
									study_event.date_created)
							VALUES (0,
									".DBConn::clean($this->getParticipantID()).",
									".DBConn::clean($this->getType()).",
									".DBConn::clean($this->getContributionID()).",
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
		$query_string = "DELETE FROM study_event
							  WHERE study_event.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getParticipantID() { return $this->participantID; }

	public function getType() { return $this->type; }

	public function getContributionID() { return $this->contributionID; }

	public function getDateCreated() { return $this->dateCreated; }
	

	# Setters
	public function setParticipantID($int) { $this->participantID = $int; }

	public function setType($str) { $this->type = $str; }

	public function setContributionID($int) { $this->contributionID = $int; }

}

?>