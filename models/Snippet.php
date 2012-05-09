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
require_once("Token.php");

class Snippet extends FactoryObject implements JSONObject {
	
	# Constants
	const MATCH_PERFECT = "perfect";
	const MATCH_LOOSE = "loose";
	
	# Static Variables
	
	
	# Instance Variables
	private $claimID; // int
	private $url; // string
	private $content; // string
	private $context; // string
	
	
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
			$data_array['url'] = "";
			$data_array['content'] = "";
			$data_array['context'] = "";
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['claimID'] = 0;
			$data_array['url'] = "";
			$data_array['content'] = "";
			$data_array['context'] = "";
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT snippets.id AS itemID,
							   snippets.claim_id AS claimID,
							   snippets.url AS url,
							   snippets.content AS content,
							   snippets.context AS context
						  FROM snippets
						 WHERE snippets.id IN (".$objectString.")";
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
			$data_array['url'] = $resultArray['url'];
			$data_array['content'] = $resultArray['content'];
			$data_array['context'] = $resultArray['context'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->claimID = isset($data_array["claimID"])?$data_array["claimID"]:0;
		$this->url = isset($data_array["url"])?$data_array["url"]:"";
		$this->content = isset($data_array["content"])?$data_array["content"]:"";
		$this->context = isset($data_array["context"])?$data_array["context"]:"";
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
			$query_string = "UPDATE snippets
							   SET snippets.claim_id = ".DBConn::clean($this->getClaimID()).",
							   AND snippets.url = ".DBConn::clean($this->getURL()).",
							   AND snippets.content = ".DBConn::clean($this->getContent()).",
							   AND snippets.content_code = ".DBConn::clean($this->getContentCode()).",
							   AND snippets.context = ".DBConn::clean($this->getContext()).",
							   AND snippets.context_code = ".DBConn::clean($this->getContextCode())."
							 WHERE snippets.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO snippets
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
			
			$mysqli->query($query_string) or print($mysqli->error);
			$this->setItemID($mysqli->insert_id);
		}
		
		// Delete old tokens
		$tokens = Token::getObjectsBySnippet($this->getItemID());
		foreach($tokens as $token)
			$token->delete();
			
		// Store new tokens
		$token_strings = Token::tokenize($this->getContent());
		foreach($token_strings as $token_string) {
			if($token_string == "") continue;
			$token = new Token();
			$token->setSnippetID($this->getItemID());
			$token->setContent($token_string);
			$token->save();
		}
		
		// Parent Operations
		return parent::save();
	}
	
	public function delete() {
		parent::delete();
		$mysqli = DBConn::connect();
		
		// Delete this record
		$query_string = "DELETE FROM snippets
							  WHERE snippets.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
		
		// Delete tokens associated with this record
		$tokens = Token::getObjectsBySnippet($this->getItemID());
		foreach($tokens as $token)
			$token->delete();
		
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
	public static function getObjectsByContext($context, $accuracy = Snippet::MATCH_PERFECT) {
		
		switch($accuracy) {
			case Snippet::MATCH_PERFECT:
				$query_string = "SELECT distinct snippets.id
								  FROM snippets
								 WHERE snippets.context_code = ".DBConn::clean(Snippet::codify($context))."
									OR ".DBConn::clean(Snippet::codify($context))." LIKE concat('%',snippets.content_code,'%')";
		
				return Snippet::getObjects($query_string);
			
			case Snippet::MATCH_LOOSE:
				$token_strings = Token::tokenize($context);
				$query_string = "SELECT snippets.id as snippetID,
										count(tokens.id) as match_count,
										(select count(tokens.id) from tokens where tokens.snippet_id = snippets.id) as total_count
								   FROM tokens
								   JOIN snippets ON (tokens.snippet_id = snippets.id)
								  WHERE tokens.content IN (".implode(",",DBConn::clean($token_strings)).")
							   GROUP BY snippets.id
								 HAVING match_count / total_count > .8";
		
				$mysqli = DBConn::connect();
				$result = $mysqli->query($query_string) or print($mysqli->error);
		
				while($resultArray = $result->fetch_assoc())
					$snippet_ids[] = $resultArray['snippetID'];
		
				return Snippet::getObjects($snippet_ids);
		}
	}
	
	public static function getObjectsByContent($content) {
		return array();
	}
	
	public static function codify($str) {
		// Remove everything but letters and numbers, and lower case
		$str = preg_replace("/[^A-Za-z0-9]/","",$str);
		$str = strtolower($str);
		return $str;
	}
	
	public static function getExpression($str) {
		// Create a regular expression which will more accuarately find the snippet
		$str = preg_replace('/\W+/',' ',$str);
		$str = trim($str);
		$str = preg_replace('/\s+/','\\W+',$str);
		
		$exp = '/[^\w\s]*'.$str.'[^\w\s]*/i';
		return $exp;
	}
	
}

?>