<?php
###
# Info:
#  Last Updated 2011
#  Daniel Schultz
#
###
require_once("DBConn.php");
require_once("FactoryObject.php");
require_once("JSONObject.php");

class VettingService extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $title; // str
	private $url; // str
	
	
	# Caches
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString) {
		$dataArrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['title'] = "";
			$dataArray['url'] = "";
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['title'] = "";
			$dataArray['url'] = "";
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT vetting_services.id AS itemID,
							   vetting_services.title AS title,
							   vetting_services.url AS url
						  FROM vetting_services
						 WHERE vetting_services.id IN (".$objectString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['title'] = $resultArray['title'];
			$dataArray['url'] = $resultArray['url'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		parent::load($dataArray);
		$this->title = isset($dataArray["title"])?$dataArray["title"]:"";
		$this->url = isset($dataArray["url"])?$dataArray["url"]:"";
	}
	
	
	# JSONObject Methods
	public function toJSON() {
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"title": '.DBConn::clean($this->getTitle()).',
			"url": '.DBConn::clean($this->getURL()).'
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
			$queryString = "UPDATE vetting_services
							   SET vetting_services.title = ".DBConn::clean($this->getTitle()).",
								   vetting_services.url = ".DBConn::clean($this->getURL())."
							 WHERE vetting_services.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($queryString) or print($mysqli->error);
		} else {
			// Create a new record
			$queryString = "INSERT INTO vetting_services
								   (vetting_services.id,
									vetting_services.title,
									vetting_services.url)
							VALUES (0,
									".DBConn::clean($this->getTitle()).",
									".DBConn::clean($this->getURL()).")";
			
			$mysqli->query($queryString) or print($mysqli->error);
			$this->setItemID($mysqli->insert_id);
		}
		
		// Parent Operations
		return parent::save();
	}
	
	public function delete() {
		parent::delete();
		$mysqli = DBConn::connect();
		
		// Delete this record
		$queryString = "DELETE FROM vetting_services
							  WHERE vetting_services.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
	}
	
	
	# Getters
	public function getTitle() { return $this->title;}
	
	public function getURL() { return $this->url;}
	
	
	# Setters
	public function setTitle($str) { $this->title = $str;}
	
	public function setURL($str) { $this->url = $str;}
	
}

?>