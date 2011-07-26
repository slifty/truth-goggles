<?php
###
# Info:
#  Last Updated 2011
#  Daniel Schultz
#
###

abstract class FactoryObject {
	
	# Constants
	// Initialization Types
	const INIT_EMPTY = -1;
	const INIT_DEFAULT = 0;
	
	
	# Instance Variables
	protected $itemID = 0;
	
	
	# Object Methods
	public function __construct($itemID = FactoryObject::INIT_EMPTY) {
		$dataArrays = static::gatherData((int)$itemID);
		$this->load($dataArrays[0]);
	}
	
	
	# FactoryObject Methods
	abstract protected static function gatherData($objectString);
	
	protected function load($dataArray) {
		// Set the item ID
		$this->setItemID(isset($dataArray["itemID"])?$dataArray["itemID"]:0);
	}
	
	
	# Data Methods
	public function delete() { return null; }
	
	public function save() {
		$this->refresh();
		return $this;
	}
	
	public function refresh() {
		$dataArrays = static::gatherData($this->getItemID());
		$this->load($dataArrays[0]);
		return $this;
	}
	
	
	# Getters
	public final function getItemID() { return $this->itemID; }
	
	public final function isUpdate() { return $this->getItemID() > 0; }
	
	
	# Setters
	protected final function setItemID($itemID) { $this->itemID = (int)$itemID; }
	
	
	# Static Methods
	public static function getObject($objectSelector) {
		// Takes in a single object ID and returns the associated object
		$dataArrays = static::gatherData((int)$objectSelector);
		
		if(sizeof($dataArrays) == 0)
			return new static();
		
		$newObject = new static();
		$newObject->load($dataArrays[0]);
		return $newObject;
	}
	
	public static function getObjects($objectSelectors) {
		// Takes in either an array of object IDs or a clean query
		
		// If it's an array clean it
		if(is_array($objectSelectors))
			foreach($objectSelectors as $key=>$objectID)
				$objectSelectors[$key] = (int)$objectID;
		elseif(!is_string($objectSelectors))
			return array();
		
		// Load the data
		$dataArrays = static::gatherData($objectSelectors);
		if(sizeof($dataArrays) == 0)
			return array();
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new static();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}	
}
?>