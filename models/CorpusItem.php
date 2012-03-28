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
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['claimID'] = 0;
			$data_array['content'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['claimID'] = 0;
			$data_array['content'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT corpus_items.id AS itemID,
							   corpus_items.claim_id AS claimID,
							   corpus_items.content AS content,
							   unix_timestamp(corpus_items.date_created) as dateCreated
						  FROM corpus_items
						 WHERE corpus_items.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['claimID'] = $resultArray['claimID'];
			$data_array['content'] = $resultArray['content'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->claimID = isset($data_array["claimID"])?$data_array["claimID"]:0;
		$this->content = isset($data_array["content"])?$data_array["content"]:"";
		$this->dateCreated = isset($data_array["dateCreated"])?$data_array["dateCreated"]:0;
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
			$query_string = "UPDATE corpus_items
							   SET corpus_items.claim_id = ".DBConn::clean($this->getClaimID()).",
							   AND corpus_items.content = ".DBConn::clean($this->getContent()).",
							 WHERE corpus_items.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO corpus_items
								   (corpus_items.id,
									corpus_items.claim_id,
									corpus_items.content,
									corpus_items.date_created)
							VALUES (0,
									".DBConn::clean($this->getClaimID()).",
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
		$query_string = "DELETE FROM corpus_items
							  WHERE corpus_items.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
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