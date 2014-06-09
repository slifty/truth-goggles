<?php

require_once(__DIR__."/DBConn.php");
require_once(__DIR__."/FactoryObject.php");
require_once(__DIR__."/JSONObject.php");

class Layer extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $dateCreated; 	// timestamp
	
	
	# Caches
	private $contributions;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT layers.id AS itemID,
							   unix_timestamp(layers.date_created) as dateCreated
						  FROM claims
						 WHERE layers.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->dateCreated = isset($data_array["dateCreated"])?$data_array["dateCreated"]:0;
	}
	
	
	# JSONObject Methods
	public function toJSON($contentStart=null, $contentLength=null) {
		$contributions = $this->getContributions();

		$contributionsJSONArray = array();
		foreach($contributions as $object)
			$contributionsJSONArray[] = $object->toJSON();
		$contributionsJSON = "[".implode(",",$contributionsJSONArray)."]";

		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"contributions": '.$contributionsJSON.'
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
		} else {
			// Create a new record
			$query_string = "INSERT INTO layers
								   (layers.id,
									layers.date_created)
							VALUES (0,
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
		$query_string = "DELETE FROM layers
							  WHERE layers.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getDateCreated() { return $this->dateCreated; }
	
	public function getContributions() {
		if($this->contributions != null)
			return $this->contributions;
		
		$query_string = "SELECT contributions.id
						  FROM contributions
						 WHERE contributions.layer_id = ".DBConn::clean($this->getItemID());
		
		return $this->contributions = Contribution::getObjects($query_string);
	}
	
	# Setters
	# 
}

?>