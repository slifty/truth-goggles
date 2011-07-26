<?php
###
# Info:
#  Last Updated 2011
#  Daniel Schultz
#
###
require_once("DBConn.php");
require_once("FactoryObject.php");
require_once("Verdict.php");
class Claim extends FactoryObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $content; // string
	private $dateCreated; //timestamp
	
	
	# Caches
	private $verdicts;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString) {
		$dataArrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['content'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['content'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT claims.id AS itemID,
							   claims.content AS content,
							   unix_timestamp(claims.date_created) as dateCreated
						  FROM claims
						 WHERE claims.id IN (".$objectString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['content'] = $resultArray['content'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		parent::load($dataArray);
		$this->content = isset($dataArray["content"])?$dataArray["content"]:"";
		$this->dateCreated = isset($dataArray["dateCreated"])?$dataArray["dateCreated"]:0;
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
			$queryString = "UPDATE claims
							   SET claims.content = ".DBConn::clean($this->getContent())."
							 WHERE claims.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($queryString) or print($mysqli->error);
		} else {
			// Create a new record
			$queryString = "INSERT INTO claims
								   (claims.id,
									claims.content,
									claims.date_created)
							VALUES (0,
									".DBConn::clean($this->getContent()).",
									NOW())";
			
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
		$queryString = "DELETE FROM claims
							  WHERE claims.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
	}
	
	
	# Getters
	public function getContent() { return $this->content;}
	
	public function getDateCreated() { return $this->dateCreated;}
	
	public function getVerdicts() {
		if($this->verdicts != null)
			return $this->verdicts;
		
		$queryString = "SELECT verdicts.id
						  FROM verdicts
						 WHERE verdicts.claim_id = ".DBConn::clean($this->getItemID());
		
		return $this->verdicts = Verdict::getObjects($queryString);
	}
	
	
	# Setters
	public function setContent($str) { $this->content = $str;}
	
	
	# Static Methods
	public static function getRelatedClaims($text) {
		return array();
	}	
}

?>