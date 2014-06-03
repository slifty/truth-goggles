<?php
namespace engine\core;
require_once('includes/engine/exceptions/ImmutableException.php');

/**
 * An object capable of being defined and populated using a factory methods.
 * 
 * @package engine\core
 */
abstract class FactoryObject {

	####
	# Contstants
	/**
	 * Indicates that the object should be created with default values
	 */
	const INIT_EMPTY = -1;	
	
	####
	# Instance Variables
	
	/**
	 * The ID of the object.
	 *
	 * This is required because it is used in object lookups.
	 * @var integer
	 */
	protected $itemId;

	/**
	 * The date that the current object was created
	 * @var timestamp
	 */
	protected $dateCreated;
	
	####
	# Object Methods
	/**
	 * Constructs the object and loads data.
	 * 
	 * This constructor invokes the gatherData and populates the object's instance variables.
	 *
	 * @uses gatherData() to load in the data associated with this object.
	 * @uses load() to populate the data gathered.
	 * @param integer $itemId Is the item being loaded.
	 */
	public function __construct($itemId = FactoryObject::INIT_EMPTY) {
		$dataArrays = static::gatherData((int)$itemId);
		if(sizeof($dataArrays) > 0)
			$this->load($dataArrays[0]);
	}
	
	###
	# Abstract Methods
	/**
	 * Reset any caches assocciated with the object
	 *
	 * This will flush any cache associated with the object
	 * 
	 * This method will alays be called any time a method is created by the factory
	 * 
	 * @return null
	 */
	abstract public function refreshCache();
	
	/**
	 * Collect data to populate this class
	 *
	 * This method must be implemented.  It communicates with the database to load the information associated with the object according to the selector
	 * 
	 * The resulting data arrays are passed into the load() method
	 * 
	 * It may load information for multiple objects at once.
	 *
	 * The FactoryObject load method expects two specific fields to be populated:
	 * * itemId
	 * * dateCreated
	 * 
	 * @param  string $selector a database-safe selector that resolves to a list of itemIds to be used in a SQL query.
	 * @return mixed[][] An array of data arrays.  Data arrays contain values for the object, keys are instance variable names.
	 * @see load
	 */
	abstract protected static function gatherData($selector);

	/**
	 * Select all objects of a particular type
	 *
	 * This method must be invoked by any class that implements FactoryObject
	 * 
	 * @return FactoryObject[]
	 */
	abstract protected static function getAllObjects();

	/**
	 * Checks to see if the object is valid
	 *
	 * Objects implementing FactoryMethod are expected to implement this method.
	 * 
	 * @return boolean true if valid, false if invalid
	 */
	abstract public function validate();

	/**
	 * Deletes the current object
	 *
	 * Objects implementing FactoryMethod are expected to implement this method.
	 *
	 * Their implementation should be sure to clean up any data related to the object.
	 * 
	 * @return null
	 */
	abstract public function delete();

	/**
	 * Returns a string that can be used when showing this item to a user.
	 * 
	 * @return string A meaningful string to identify this object to a user
	 */
	public abstract function getItemTitle();
	
	
	####
	# Data Methods
	/**
	 * Loads relevant data into the object
	 *
	 * At this level it will only populate $itemId and $dateCreated.  Inherited classes must override this method to populate more specific instance variables.
	 *
	 * Any methods that override this method should be sure to call the super's load.
	 * 
	 * @param  mixed[] $dataArray The data to insert into the 
	 * @return null
	 */
	protected function load($dataArray) {
		// Reset any caches
		$this->refreshCache();

		// Set the item ID
		$this->itemId = isset($dataArray["itemId"])?$dataArray["itemId"]:0;
		$this->dateCreated = isset($dataArray["dateCreated"])?$dataArray["dateCreated"]:0;
	}
	
	/**
	 * Save the object
	 * 
	 * @return null
	 * @uses refresh() to reset variables associated with this method
	 */
	public function save() {
		$this->refresh();
		return $this;
	}
	
	/**
	 * Reloads all data associated with the object
	 * 
	 * @return null
	 */
	public function refresh() {
		$dataArrays = static::gatherData($this->getItemId());
		$this->load($dataArrays[0]);
	}
	
	####
	# Getters	
	/**
	 * A getter method to access the item ID.
	 * 
	 * @return integer Is the item's ID
	 */
	public final function getItemId() { return $this->itemId; }

	/**
	 * A getter method to access the item's Creation Date.
	 * 
	 * @return timestamp Is the items creation date
	 */
	public final function getDateCreated() { return $this->dateCreated; }
	
	/**
	 * A helper method to know if the item has been saved in the past.
	 *  
	 * @return boolean true if the object has been saved in the past.  false if the object has not been saved in the past.
	 */
	public final function isUpdate() { return ($this->getItemId() > 0); }	

	####
	# Setters
	/**
	 * A setter method to specify the item ID
	 * 
	 * @param [type] $this->itemId [description]
	 * @throws \engine\exceptions\ImmutableException If this item already has an $itemId
	 */
	protected final function setItemId($itemId) {
		if($this->getItemId() > 0)
			throw new \engine\exceptions\ImmutableException("You cannot specify an item ID for an item that has already been saved.");
		$this->itemId = (int)$itemId;
	}
	
	####
	# Static Methods
	/**
	 * Returns a single object.
	 *
	 * Takes in a single object ID and returns the associated object.
	 * 
	 * @param  integer|string $objectSelector An ID or the SQL statement to specify the object ID to be selected.
	 * @return null|FactoryObject the specified object, or null if the object could not be found.
	 */
	public static function getObject($objectSelector) {
		$dataArrays = static::gatherData($objectSelector);
		if(sizeof($dataArrays) == 0)
			return null;
		
		$newObject = new static();
		$newObject->load($dataArrays[0]);
		return $newObject;
	}
	
	/**
	 * Returns a list of objects.
	 *
	 * Takes in a collection of object IDs and returns the associated objects.
	 * 
	 * @param  integer[]|string $objectSelector An array of IDs or the SQL statement to specify the object IDs to be selected.
	 * @return FactoryObject[] a list of the specified objects (this list can be empty if no objects matched)
	 */
	public static function getObjects($objectSelectors) {
		
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