<?php
###
# Info:
#  Last Updated 2011
#  Daniel Schultz
#
###
require_once(__DIR__."/DBConn.php");
require_once(__DIR__."/FactoryObject.php");
require_once(__DIR__."/JSONObject.php");

class VettingService extends FactoryObject implements JSONObject {
	
	# Constants
	
	
	# Static Variables
	
	
	# Instance Variables
	private $title; // str
	private $url; // str
	private $logo_url; // str
	
	
	# Caches
	
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$data_arrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['title'] = "";
			$data_array['url'] = "";
			$data_array['logo_url'] = "";
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$data_array = array();
			$data_array['itemID'] = 0;
			$data_array['title'] = "";
			$data_array['url'] = "";
			$data_array['logo_url'] = "";
			$data_arrays[] = $data_array;
			return $data_arrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$query_string = "SELECT vetting_services.id AS itemID,
							   vetting_services.title AS title,
							   vetting_services.url AS url,
							   vetting_services.logo_url AS logo_url
						  FROM vetting_services
						 WHERE vetting_services.id IN (".$objectString.")";
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
			$data_array['url'] = $resultArray['url'];
			$data_array['logo_url'] = $resultArray['logo_url'];
			$data_arrays[] = $data_array;
		}
		
		$result->free();
		return $data_arrays;
	}
	
	public function load($data_array) {
		parent::load($data_array);
		$this->title = isset($data_array["title"])?$data_array["title"]:"";
		$this->url = isset($data_array["url"])?$data_array["url"]:"";
		$this->logo_url = isset($data_array["logo_url"])?$data_array["logo_url"]:"";
	}
	
	
	# JSONObject Methods
	public function toJSON() {
		$json = '{
			"id": '.DBConn::clean($this->getItemID()).',
			"title": '.DBConn::clean($this->getTitle()).',
			"url": '.DBConn::clean($this->getURL()).',
			"logo_url": '.DBConn::clean($this->getLogoURL()).'
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
			$query_string = "UPDATE vetting_services
							   SET vetting_services.title = ".DBConn::clean($this->getTitle()).",
								   vetting_services.url = ".DBConn::clean($this->getURL()).",
								   vetting_services.logo_url = ".DBConn::clean($this->getLogoURL())."
							 WHERE vetting_services.id = ".DBConn::clean($this->getItemID());
							
			$mysqli->query($query_string) or print($mysqli->error);
		} else {
			// Create a new record
			$query_string = "INSERT INTO vetting_services
								   (vetting_services.id,
									vetting_services.title,
									vetting_services.url,
									vetting_services.logo_url)
							VALUES (0,
									".DBConn::clean($this->getTitle()).",
									".DBConn::clean($this->getURL()).",
									".DBConn::clean($this->getLogoURL()).")";
			
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
		$query_string = "DELETE FROM vetting_services
							  WHERE vetting_services.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($query_string);
	}
	
	
	# Getters
	public function getTitle() { return $this->title; }
	
	public function getURL() { return $this->url; }
	
	public function getLogoURL() { return $this->logo_url; }
	
	
	# Setters
	public function setTitle($str) { $this->title = $str; }
	
	public function setURL($str) { $this->url = $str; }
	
	public function setLogoURL($str) { $this->logo_url = $str; }
	
	
	# Static Loaders
	public static function getObjectByTitle($str) {
		$query_string = "SELECT vetting_services.id
						  FROM vetting_services
						 WHERE vetting_services.title = ".DBConn::clean($str);
		return array_pop(VettingService::getObjects($query_string));
	}
	
}

?>