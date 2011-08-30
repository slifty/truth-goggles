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
require_once("Claim.php");

class CorpusItem extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $claimID; // int
	private $content; // string
	private $dateCreated; //timestamp
	
	
	# Caches
	private $claim;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString) {
		$dataArrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['claimID'] = 0;
			$dataArray['content'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['claimID'] = 0;
			$dataArray['content'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT corpus_items.id AS itemID,
							   corpus_items.claim_id AS claimID,
							   corpus_items.content AS content,
							   unix_timestamp(corpus_items.date_created) as dateCreated
						  FROM corpus_items
						 WHERE corpus_items.id IN (".$objectString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['claimID'] = $resultArray['claimID'];
			$dataArray['content'] = $resultArray['content'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		parent::load($dataArray);
		$this->claimID = isset($dataArray["claimID"])?$dataArray["claimID"]:0;
		$this->content = isset($dataArray["content"])?$dataArray["content"]:"";
		$this->dateCreated = isset($dataArray["dateCreated"])?$dataArray["dateCreated"]:0;
	}
	
	
	# JSONObject Methods
	public function toJSON() {
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"claim_id": '.DBConn::clean($this->getClaimID()).',
			"content": '.DBConn::clean($this->getContent()).',
			"date_created": '.DBConn::clean($this->getDateCreated()).'
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
			$queryString = "UPDATE corpus_items
							   SET corpus_items.claim_id = ".DBConn::clean($this->getClaimID()).",
							   AND corpus_items.content = ".DBConn::clean($this->getContent()).",
							 WHERE corpus_items.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($queryString) or print($mysqli->error);
		} else {
			// Create a new record
			$queryString = "INSERT INTO corpus_items
								   (corpus_items.id,
									corpus_items.claim_id,
									corpus_items.content,
									corpus_items.date_created)
							VALUES (0,
									".DBConn::clean($this->getClaimID()).",
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
		$queryString = "DELETE FROM corpus_items
							  WHERE corpus_items.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
	}
	
	
	# Getters
	public function getClaimID() { return $this->claimID; }
	
	public function getContent() { return $this->content; }
	
	public function getDateCreated() { return $this->dateCreated; }

	public function getClaim() {
		if($this->claim != null)
			return $this->claim;
		return $this->claim = Claim::getObject($this->getClaimID());
	}
	
	# Setters
	public function setClaimID($int) { $this->claimID = $int; }

	public function setContent($str) { $this->content = $str; }
	
	
}

?>