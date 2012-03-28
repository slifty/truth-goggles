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
require_once("Snippet.php");

class Token extends FactoryObject implements JSONObject {
	
	# Constants
	const TOKEN_UNIQUE = true;
	const TOKEN_ALL = false;
	
	# Static Variables
	
	
	# Instance Variables
	private $snippetID; // int
	private $content; // string
	
	
	# Caches
	private $snippet;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['snippetID'] = 0;
			$data_array['content'] = "";
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['snippetID'] = 0;
			$data_array['content'] = "";
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT tokens.id AS itemID,
							   tokens.snippet_id AS snippetID,
							   tokens.content AS content
						  FROM tokens
						 WHERE tokens.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['snippetID'] = $resultArray['snippetID'];
			$data_array['content'] = $resultArray['content'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->claimID = isset($data_array["snippetID"])?$data_array["snippetID"]:0;
		$this->content = isset($data_array["content"])?$data_array["content"]:"";
	}
	
	
	# JSONObject Methods
	public function toJSON($contentStart=null, $contentLength=null) {
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"snippet_id": '.DBConn::clean($this->getSnippetID()).',
			"content": '.DBConn::clean($this->getContent()).'
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
			$query_string = "UPDATE tokens
							   SET tokens.snippet_id = ".DBConn::clean($this->getSnippetID()).",
							   AND tokens.content = ".DBConn::clean($this->getContent()).",
							 WHERE tokens.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO tokens
								   (tokens.id,
									tokens.snippet_id,
									tokens.content)
							VALUES (0,
									".DBConn::clean($this->getSnippetID()).",
									".DBConn::clean($this->getContent()).")";
			
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
		$query_string = "DELETE FROM tokens
							  WHERE tokens.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getSnippetID() { return $this->snippetID; }
	
	public function getContent() { return $this->content; }
	
	public function getSnippet() {
		if($this->snippet != null)
			return $this->snippet;
		return $this->snippet = Snippet::getObject($this->getSnippetID());
	}
	
	
	# Setters
	public function setSnippetID($int) { $this->snippetID = $int; }
	
	public function setContent($str) {$this->content = $str; }
	
	
	# Static Methods
	public static function getObjectsBySnippet($int) {
		$query_string = "SELECT distinct tokens.id
						  FROM tokens
						 WHERE tokens.snippet_id = ".DBConn::clean($int);
		
		return Token::getObjects($query_string);
	}
	
	public static function codify($str) {
		// Remove everything but letters and numbers, and lower case
		$str = strtolower($str);
		$str = preg_replace('/[^\w\s]/','', $str);
		$str = preg_replace('/\s+/',' ', $str);
		return $str;
	}
	
	public static function tokenize($str, $unique = Token::TOKEN_UNIQUE) {
		$token_strings = preg_split("/\s+/",Token::codify($str));
		if($unique == Token::TOKEN_UNIQUE) $token_strings = array_unique($token_strings);
		while(($pos = array_search('', $token_strings)) !== false) unset($token_strings[$pos]);
		$token_strings = array_values($token_strings);
		return $token_strings;
	}
	
	public static function getStopWords() {
		$stopWords = array();
		$stopWords[] = "a";
		$stopWords[] = "been";
		$stopWords[] = "fact";
		$stopWords[] = "has";
		$stopWords[] = "in";
		$stopWords[] = "is";
		$stopWords[] = "it";
		$stopWords[] = "its";
		$stopWords[] = "not";
		$stopWords[] = "of";
		$stopWords[] = "said";
		$stopWords[] = "says";
		$stopWords[] = "that";
		$stopWords[] = "the";
		$stopWords[] = "to";
		$stopWords[] = "were";
		return $stopWords;
	}
	
}

?>