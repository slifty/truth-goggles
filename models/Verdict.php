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
require_once("ResultClass.php");
require_once("VettingService.php");

class Verdict extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $claimID; // int
	private $resultClassID; //int
	private $vettingServiceID; //int
	private $shortReason; // str
	private $longReason; // str
	private $url; // str
	private $dateCreated; // timestamp
	
	
	# Caches
	private $result;
	private $claim;
	private $vettingService;
    
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['claimID'] = 0;
			$data_array['resultClassID'] = 0;
			$data_array['vettingServiceID'] = 0;
			$data_array['shortReason'] = "";
			$data_array['longReason'] = "";
			$data_array['url'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['claimID'] = 0;
			$data_array['resultClassID'] = 0;
			$data_array['vettingServiceID'] = 0;
			$data_array['shortReason'] = "";
			$data_array['longReason'] = "";
			$data_array['url'] = "";
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT verdicts.id AS itemID,
							   verdicts.claim_id AS claimID,
   							   verdicts.result_class_id AS resultClassID,
   							   verdicts.vetting_service_id AS vettingServiceID,
   							   verdicts.short_reason AS shortReason,
   							   verdicts.long_reason AS longReason,
							   verdicts.url AS url,
							   unix_timestamp(verdicts.date_created) as dateCreated
						  FROM verdicts
						 WHERE verdicts.id IN (".$objectString.")";
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
			$data_array['resultClassID'] = $resultArray['resultClassID'];
			$data_array['vettingServiceID'] = $resultArray['vettingServiceID'];
			$data_array['shortReason'] = $resultArray['shortReason'];
			$data_array['longReason'] = $resultArray['longReason'];
			$data_array['url'] = $resultArray['url'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->claimID = isset($data_array["claimID"])?$data_array["claimID"]:0;
		$this->resultClassID = isset($data_array["resultClassID"])?$data_array["resultClassID"]:0;
		$this->vettingServiceID = isset($data_array["vettingServiceID"])?$data_array["vettingServiceID"]:0;
		$this->shortReason = isset($data_array["shortReason"])?$data_array["shortReason"]:"";
		$this->longReason = isset($data_array["longReason"])?$data_array["longReason"]:"";
		$this->url = isset($data_array["url"])?$data_array["url"]:"";
		$this->dateCreated = isset($data_array["dateCreated"])?$data_array["dateCreated"]:0;
	}
	
	
	# JSONObject Methods
	public function toJSON() {
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"short_reason": '.DBConn::clean($this->getShortReason()).',
			"long_reason": '.DBConn::clean($this->getLongReason()).',
			"url": '.DBConn::clean($this->getURL()).',
			"date_created": '.DBConn::clean($this->getDateCreated()).',
			"result_class": '.$this->getResultClass()->toJSON().',
			"vetting_service": '.$this->getVettingService()->toJSON().'
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
			$query_string = "UPDATE verdicts
							   SET verdicts.claim_id = ".DBConn::clean($this->getClaimID()).",
								   verdicts.result_class_id = ".DBConn::clean($this->getResultClassID()).",
								   verdicts.vetting_service_id = ".DBConn::clean($this->getVettingServiceID()).",
								   verdicts.short_reason = ".DBConn::clean($this->getShortReason()).",
								   verdicts.long_reason = ".nl2br(DBConn::clean($this->getLongReason())).",
								   verdicts.url = ".DBConn::clean($this->getURL())."
							 WHERE verdicts.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO verdicts
								   (verdicts.id,
									verdicts.claim_id,
									verdicts.result_class_id,
									verdicts.vetting_service_id,
									verdicts.short_reason,
									verdicts.long_reason,
									verdicts.url,
									verdicts.date_created)
							VALUES (0,
									".DBConn::clean($this->getClaimID()).",
									".DBConn::clean($this->getResultClassID()).",
									".DBConn::clean($this->getVettingServiceID()).",
									".DBConn::clean($this->getShortReason()).",
									".DBConn::clean($this->getLongReason()).",
									".DBConn::clean($this->getURL()).",
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
		$query_string = "DELETE FROM verdicts
							  WHERE verdicts.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getClaimID() { return $this->claimID;}
	
	public function getVettingServiceID() { return $this->vettingServiceID;}
	
	public function getResultClassID() { return $this->resultClassID;}
	
	public function getShortReason() { return $this->shortReason;}
	
	public function getLongReason() { return $this->longReason;}
	
	public function getURL() { return $this->url;}
	
	public function getDateCreated() { return $this->dateCreated;}
	
	public function getResultClass() {
		if($this->result != null)
			return $this->result;
		return $this->result = ResultClass::getObject($this->getResultClassID());
	}

	public function getClaim() {
		if($this->claim != null)
			return $this->claim;
		return $this->claim = Claim::getObject($this->getClaimID());
	}
	
	public function getVettingService() {
		if($this->vettingService != null)
			return $this->vettingService;
		return $this->vettingService = VettingService::getObject($this->getVettingServiceID());
	}
	
	
	# Setters
	public function setClaimID($int) { $this->claimID = $int;}
	
	public function setResultClassID($int) { $this->resultClassID = $int;}
	
	public function setVettingServiceID($int) { $this->vettingServiceID = $int;}
	
	public function setShortReason($str) { $this->shortReason = $str;}
	
	public function setLongReason($str) { $this->longReason = $str;}
	
	public function setURL($str) { $this->url = $str;}
	
}

?>