<?php

class ResultClass extends FactoryObject implements JSONObject {
	
	# Constants
	const CLASS_TRUE = "true";
	const CLASS_MOSTLY_TRUE = "mostly_true";
	const CLASS_HALF_TRUE = "half_true";
	const CLASS_BARELY_TRUE = "barely_true";
	const CLASS_FALSE = "false";
	const CLASS_PANTS_ON_FIRE = "pants_on_fire";
	
	
	# Static Variables
	
	
	# Instance Variables
	private $title; // str
	private $description; // str
	private $class; // str
	
	
	# Caches
	private $result;
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['title'] = "";
			$data_array['description'] = "";
			$data_array['class'] = "";
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['title'] = "";
			$data_array['description'] = "";
			$data_array['class'] = "";
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT result_classes.id AS itemID,
								result_classes.title AS title,
								result_classes.description AS description,
								result_classes.class AS class
						   FROM result_classes
						  WHERE result_classes.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($query_string)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$data_array = array();
			$data_array['itemID'] = $resultArray['itemID'];
			$data_array['title'] = $resultArray['title'];
			$data_array['description'] = $resultArray['description'];
			$data_array['class'] = $resultArray['class'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->title = isset($data_array["title"])?$data_array["title"]:"";
		$this->description = isset($data_array["description"])?$data_array["description"]:"";
		$this->class = isset($data_array["class"])?$data_array["class"]:"";
	}
	
	
	# JSONObject Methods
	public function toJSON() {
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"title": '.DBConn::clean($this->getTitle()).',
			"description": '.DBConn::clean($this->getDescription()).',
			"class": '.DBConn::clean($this->getClass()).'
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
			$query_string = "UPDATE result_classes
							   SET result_classes.title = ".DBConn::clean($this->getTitle()).",
								   result_classes.description = ".DBConn::clean($this->getDescription()).",
								   result_classes.class = ".DBConn::clean($this->getClass()).",
							 WHERE result_classes.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO result_classes
								   (result_classes.id,
									result_classes.title,
									result_classes.description,
									result_classes.class)
							VALUES (0,
									".DBConn::clean($this->getTitle()).",
									".DBConn::clean($this->getDescription()).",
									".DBConn::clean($this->getClass()).")";
			
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
		$query_string = "DELETE FROM result_classes
							  WHERE result_classes.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getTitle() { return $this->title;}
	
	public function getDescription() { return $this->description;}
	
	public function getClass() { return $this->class;}
	
	
	# Setters
	public function setTitle($str) { $this->title = $str;}
	
	public function setDescription($str) { $this->description = $str;}
	
	public function setClass($str) { $this->class = $str;}
	
	
	# Static Methods
	public static function getObjectByClass($class) {
		$query_string = "SELECT result_classes.id as itemID 
						   FROM result_classes
						  WHERE result_classes.class = ".DBConn::clean($class);
		return array_pop(ResultClass::getObjects($query_string));
	}
}

?>