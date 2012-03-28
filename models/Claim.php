<?php
###
# Info:
#  Last Updated 2011
#  Daniel Schultz
#
###

require_once("conf.php");
require_once("DBConn.php");
require_once("FactoryObject.php");
require_once("JSONObject.php");
require_once("Verdict.php");
require_once("Token.php");
require_once("Snippet.php");

class Claim extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $content; // string
	private $dateRecorded; // timestamp
	private $dateCreated; //timestamp
	
	
	# Caches
	private $verdicts;
	private $snippets;
	private $corpusItems;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['content'] = "";
			$data_array['dateRecorded'] = 0;
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['content'] = "";
			$data_array['dateRecorded'] = 0;
			$data_array['dateCreated'] = 0;
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT claims.id AS itemID,
							   claims.content AS content,
							   unix_timestamp(claims.date_recorded) as dateRecorded,
							   unix_timestamp(claims.date_created) as dateCreated
						  FROM claims
						 WHERE claims.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['content'] = $resultArray['content'];
			$data_array['dateRecorded'] = $resultArray['dateRecorded'];
			$data_array['dateCreated'] = $resultArray['dateCreated'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->content = isset($data_array["content"])?$data_array["content"]:"";
		$this->dateRecorded = isset($data_array["dateRecorded"])?$data_array["dateRecorded"]:0;
		$this->dateCreated = isset($data_array["dateCreated"])?$data_array["dateCreated"]:0;
	}
	
	
	# JSONObject Methods
	public function toJSON($contentStart=null, $contentLength=null) {
		$verdicts = $this->getVerdicts();
		$verdictsJSONArray = array();
		foreach($verdicts as $verdict)
			$verdictsJSONArray[] = $verdict->toJSON();
		$verdictsJSON = "[".implode(",",$verdictsJSONArray)."]";
		
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"content": '.DBConn::clean($this->getContent()).',
			"date_recorded": '.DBConn::clean($this->getDateRecorded()).',
			"verdicts": '.$verdictsJSON.'
			'.(($contentStart)?', "content_start": '.DBConn::clean($contentStart):'').'
			'.(($contentLength)?', "content_length": '.DBConn::clean($contentLength):'').'
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
			$query_string = "UPDATE claims
							   SET claims.content = ".DBConn::clean($this->getContent())."
							 WHERE claims.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO claims
								   (claims.id,
									claims.content,
									claims.date_recorded,
									claims.date_created)
							VALUES (0,
									".DBConn::clean($this->getContent()).",
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
		$query_string = "DELETE FROM claims
							  WHERE claims.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getContent() { return $this->content; }
	
	public function getDateRecorded() { return $this->dateRecorded; }

	public function getDateCreated() { return $this->dateCreated; }
	
	public function getVerdicts() {
		if($this->verdicts != null)
			return $this->verdicts;
		
		$query_string = "SELECT verdicts.id
						  FROM verdicts
						 WHERE verdicts.claim_id = ".DBConn::clean($this->getItemID());
		
		return $this->verdicts = Verdict::getObjects($query_string);
	}
	
	public function getSnippets() {
		if($this->snippets != null)
			return $this->snippets;
		
		$query_string = "SELECT snippets.id
						  FROM snippets
						 WHERE snippets.claim_id = ".DBConn::clean($this->getItemID());
		
		return $this->snippets = Snippet::getObjects($query_string);
	}
	
	public function getCorpusItems() {
		if($this->corpusItems != null)
			return $this->corpusItems;
		
		$query_string = "SELECT corpus_items.id
						  FROM corpus_items
						 WHERE corpus_items.claim_id = ".DBConn::clean($this->getItemID());
		
		return $this->corpusItems = CorpusItem::getObjects($query_string);
	}
	
	
	# Setters
	public function setContent($str) { $this->content = $str; }
	
	public function setDateRecorded($timestamp) { $this->dateRecorded = $timestamp; }
	
	
	# Static Methods
	public static function getObjectByContent($class) {
		$query_string = "SELECT claims.id as itemID 
						   FROM claims
						  WHERE claims.content = ".DBConn::clean($class);
		return array_pop(Claim::getObjects($query_string));
	}
	
}

?>