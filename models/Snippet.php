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

class Snippet extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $claimID; // int
	private $url; // string
	private $content; // string
	private $context; // string
	
	
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
			$dataArray['url'] = "";
			$dataArray['content'] = "";
			$dataArray['context'] = "";
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['claimID'] = 0;
			$dataArray['url'] = "";
			$dataArray['content'] = "";
			$dataArray['context'] = "";
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT snippets.id AS itemID,
							   snippets.claim_id AS claimID,
							   snippets.url AS url,
							   snippets.content AS content,
							   snippets.context AS context
						  FROM snippets
						 WHERE snippets.id IN (".$objectString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['claimID'] = $resultArray['claimID'];
			$dataArray['url'] = $resultArray['url'];
			$dataArray['content'] = $resultArray['content'];
			$dataArray['context'] = $resultArray['context'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		parent::load($dataArray);
		$this->claimID = isset($dataArray["claimID"])?$dataArray["claimID"]:0;
		$this->url = isset($dataArray["url"])?$dataArray["url"]:"";
		$this->content = isset($dataArray["content"])?$dataArray["content"]:"";
		$this->context = isset($dataArray["context"])?$dataArray["context"]:"";
	}
	
	
	# JSONObject Methods
	public function toJSON($contentStart=null, $contentLength=null) {
		// TODO -- Find a more elegant way to have contentStart and contentLenght be a part of a snippet
		
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"content": '.DBConn::clean($this->getContent()).',
			"context": '.DBConn::clean($this->getContext()).',
			"url": '.DBConn::clean($this->getURL()).',
			"claim": '.$this->getClaim()->toJSON().'
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
			$queryString = "UPDATE snippets
							   SET snippets.claim_id = ".DBConn::clean($this->getClaimID()).",
							   AND snippets.url = ".DBConn::clean($this->getURL()).",
							   AND snippets.content = ".DBConn::clean($this->getContent()).",
							   AND snippets.content_code = ".DBConn::clean($this->getContentCode()).",
							   AND snippets.context = ".DBConn::clean($this->getContext()).",
							   AND snippets.context_code = ".DBConn::clean($this->getContextCode())."
							 WHERE snippets.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($queryString) or print($mysqli->error);
		} else {
			// Create a new record
			$queryString = "INSERT INTO snippets
								   (snippets.id,
									snippets.claim_id,
									snippets.url,
									snippets.content,
									snippets.content_code,
									snippets.context,
									snippets.context_code)
							VALUES (0,
									".DBConn::clean($this->getClaimID()).",
									".DBConn::clean($this->getURL()).",
									".DBConn::clean($this->getContent()).",
									".DBConn::clean($this->getContentCode()).",
									".DBConn::clean($this->getContext()).",
									".DBConn::clean($this->getContextCode()).")";
			
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
		$queryString = "DELETE FROM snippets
							  WHERE snippets.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
	}
	
	
	# Getters
	public function getClaimID() { return $this->claimID; }
	
	public function getURL() { return $this->url; }
	
	public function getContent() { return $this->content; }
	
	public function getContentCode() { return Snippet::codify($this->content); }
	
	public function getContext() { return $this->context; }
	
	public function getContextCode() { return Snippet::codify($this->context); }
	
	public function getClaim() {
		if($this->claim != null)
			return $this->claim;
		return $this->claim = Claim::getObject($this->getClaimID());
	}
	
	
	# Setters
	public function setClaimID($int) { $this->claimID = $int; }
	
	public function setURL($str) { $this->url = $str; }
	
	public function setContent($str) {$this->content = $str; }
	
	public function setContext($str) { $this->context = $str; }
	
	
	# Static Methods
	public static function getSnippetsByContext($context) {
		$queryString = "SELECT distinct snippets.id
						  FROM snippets
						 WHERE snippets.context_code = ".DBConn::clean(Snippet::codify($context))."
							OR ".DBConn::clean(Snippet::codify($context))." LIKE concat('%',snippets.content_code,'%')";
		return Snippet::getObjects($queryString);
	}
	
	public static function getSnippetsByContent($content) {
		return array();
	}
	
	public static function codify($str) {
		// Remove everything but letters and numbers:
		return preg_replace("/[^A-Za-z0-9]/","",$str);
	}
	
}

?>