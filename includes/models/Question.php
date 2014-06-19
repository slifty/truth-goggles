<?php

require_once(__DIR__."/DBConn.php");
require_once(__DIR__."/FactoryObject.php");
require_once(__DIR__."/JSONObject.php");

class Question extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $contributionID;// int
	private $content; 		// string
	private $dateCreated; 	// timestamp
	
	
	# Caches
	private $contribution;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['contributionID'] = 0;
			$data_array['content'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['contributionID'] = 0;
			$data_array['content'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT questions.id AS itemID,
							   questions.contribution_id AS contributionID,
							   questions.content AS content,
							   unix_timestamp(questions.date_created) as dateCreated
						  FROM questions
						 WHERE questions.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['contributionID'] = $resultArray['contributionID'];
			$data_array['content'] = $resultArray['content'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->contributionID = isset($data_array["contributionID"])?$data_array["contributionID"]:0;
		$this->content = isset($data_array["content"])?$data_array["content"]:"";
		$this->dateCreated = isset($data_array["dateCreated"])?$data_array["dateCreated"]:0;
	}
	
	
	# JSONObject Methods
	public function toJSON($contentStart=null, $contentLength=null) {

		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"contribution_id": '.DBConn::clean($this->getContributionID()).',
			"content": '.DBConn::clean($this->getContent()).'
		}';
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
			$query_string = "UPDATE questions
							   SET questions.contribution_id = ".DBConn::clean($this->getContributionID()).",
							       questions.content = ".DBConn::clean($this->getContent())."
							 WHERE questions.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO questions
								   (questions.id,
									questions.contribution_id,
									questions.content,
									questions.date_created)
							VALUES (0,
									".DBConn::clean($this->getContributionID()).",
									".DBConn::clean($this->getContent()).",
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
		$query_string = "DELETE FROM questions
							  WHERE questions.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getContributionID() { return $this->contributionID; }

	public function getContent() { return $this->content; }

	public function getDateCreated() { return $this->dateCreated; }
	

	# Setters
	public function setContributionID($int) { $this->contributionID = $int; }

	public function setContent($str) { $this->content = $str; }

}

?>