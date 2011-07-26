<?php
###
# Info:
#  Last Updated 2011
#  Daniel Schultz
#
###
require_once("DBConn.php");
require_once("FactoryObject.php");
require_once("Claim.php");
require_once("Verdict.php");

class ResultClass extends FactoryObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $title; // str
	private $description; // str
	private $class; // str
	
	
	# Caches
	private $result;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString) {
		$dataArrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['title'] = "";
			$dataArray['description'] = "";
			$dataArray['class'] = "";
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['title'] = "";
			$dataArray['description'] = "";
			$dataArray['class'] = "";
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT result_classes.id AS itemID,
							   result_classes.title AS title,
							   result_classes.description AS description,
							   result_classes.class AS class
						  FROM result_classes
						 WHERE result_classes.id IN (".$objectString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['title'] = $resultArray['title'];
			$dataArray['description'] = $resultArray['description'];
			$dataArray['class'] = $resultArray['class'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		parent::load($dataArray);
		$this->title = isset($dataArray["title"])?$dataArray["title"]:"";
		$this->description = isset($dataArray["description"])?$dataArray["description"]:"";
		$this->class = isset($dataArray["class"])?$dataArray["class"]:"";
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
			$queryString = "UPDATE result_classes
							   SET result_classes.title = ".DBConn::clean($this->getTitle()).",
								   result_classes.description = ".DBConn::clean($this->getDescription()).",
								   result_classes.class = ".DBConn::clean($this->getClass()).",
							 WHERE result_classes.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($queryString) or print($mysqli->error);
		} else {
			// Create a new record
			$queryString = "INSERT INTO result_classes
								   (result_classes.id,
									result_classes.title,
									result_classes.description,
									result_classes.class)
							VALUES (0,
									".DBConn::clean($this->getTitle()).",
									".DBConn::clean($this->getDescription()).",
									".DBConn::clean($this->getClass()).")";
			
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
		$queryString = "DELETE FROM result_classes
							  WHERE result_classes.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
	}
	
	
	# Getters
	public function getTitle() { return $this->title;}
	
	public function getDescription() { return $this->description;}
	
	public function getClass() { return $this->class;}
	
	
	# Setters
	public function setTitle($str) { $this->title = $str;}
	
	public function setDescription($str) { $this->description = $str;}
	
	public function setClass($str) { $this->class = $str;}
	
}

?>