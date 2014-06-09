<?php

require_once(__DIR__."/DBConn.php");
require_once(__DIR__."/FactoryObject.php");
require_once(__DIR__."/JSONObject.php");

require_once(__DIR__."/Argument.php");
require_once(__DIR__."/Question.php");
require_once(__DIR__."/Statement.php");
require_once(__DIR__."/Evidence.php");
require_once(__DIR__."/Vardict.php");

class Contribution extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $layerId; 		// int
	private $dateCreated; 	// timestamp
	
	
	# Caches
	private $statements;
	private $evidence;
	private $questions;
	private $arguments;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['layerID'] = 0;
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['layerID'] = 0;
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT contributions.id AS itemID,
							   contributions.layer_id AS layerID,
							   unix_timestamp(contributions.date_created) as dateCreated
						  FROM contributions
						 WHERE contributions.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['layerID'] = $resultArray['layerID'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->layerID = isset($data_array["layerID"])?$data_array["layerID"]:0;
		$this->dateCreated = isset($data_array["dateCreated"])?$data_array["dateCreated"]:0;
	}
	
	
	# JSONObject Methods
	public function toJSON($contentStart=null, $contentLength=null) {
		$statements = $this->getStatements();
		$evidence = $this->getEvidence();
		$questions = $this->getQuestions();
		$arguments = $this->getArguments();

		$statementsJSONArray = array();
		foreach($statements as $object)
			$statementsJSONArray[] = $object->toJSON();
		$statementsJSON = "[".implode(",",$statementsJSONArray)."]";

		$evidenceJSONArray = array();
		foreach($evidence as $object)
			$evidenceJSONArray[] = $object->toJSON();
		$evidenceJSON = "[".implode(",",$evidenceJSONArray)."]";

		$questionsJSONArray = array();
		foreach($questions as $object)
			$questionsJSONArray[] = $object->toJSON();
		$questionsJSON = "[".implode(",",$questionsJSONArray)."]";

		$argumentsJSONArray = array();
		foreach($argumentsJSONArray as $object)
			$argumentsJSONArray[] = $object->toJSON();
		$argumentsJSON = "[".implode(",",$argumentsJSONArray)."]";
		
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"layer_id": '.DBConn::clean($this->getLayerID()).',
			"statements": '.$statementsJSON.',
			"evidence": '.$evidenceJSON.',
			"questions": '.$questionsJSON.',
			"arguments": '.$argumentsJSON.'
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
			$query_string = "UPDATE contributions
							   SET contributions.layer_id = ".DBConn::clean($this->getLayerID())."
							 WHERE contributions.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO contributions
								   (contributions.id,
									contributions.layer_id,
									contributions.date_created)
							VALUES (0,
									".DBConn::clean($this->getLayerID()).",
									FROM_UNIXTIME(".DBConn::clean($this->getDateRecorded())."),
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
		$query_string = "DELETE FROM contributions
							  WHERE contributions.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getLayerID() { return $this->layerID; }

	public function getDateCreated() { return $this->dateCreated; }
	
	public function getStatements() {
		if($this->statements != null)
			return $this->statements;
		
		$query_string = "SELECT statements.id
						  FROM statements
						 WHERE statements.contribution_id = ".DBConn::clean($this->getItemID());
		
		return $this->statements = Statement::getObjects($query_string);
	}
	
	public function getEvidence() {
		if($this->evidence != null)
			return $this->evidence;
		
		$query_string = "SELECT evidence.id
						  FROM evidence
						 WHERE evidence.contribution_id = ".DBConn::clean($this->getItemID());
		
		return $this->evidence = Evidence::getObjects($query_string);
	}
	
	public function getQuestions() {
		if($this->questions != null)
			return $this->questions;
		
		$query_string = "SELECT questions.id
						  FROM questions
						 WHERE questions.contribution_id = ".DBConn::clean($this->getItemID());
		
		return $this->questions = Questions::getObjects($query_string);
	}
	
	public function getArguments() {
		if($this->arguments != null)
			return $this->arguments;
		
		$query_string = "SELECT arguments.id
						  FROM arguments
						 WHERE arguments.contribution_id = ".DBConn::clean($this->getItemID());
		
		return $this->arguments = Arguments::getObjects($query_string);
	}
	
	
	# Setters
	public function setLayerID($int) { $this->layerID = $int; }

}

?>