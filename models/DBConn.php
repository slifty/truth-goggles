<?php
###
# Info:
#  Last Updated 2011
#  Daniel Schultz
#
# Comments:
###
include_once("conf.php");
class DBConn {
	
	# Constants
	// Clean types
	const CLEAN_INPUT = "input";
	const CLEAN_VALIDATION = "validation";
	
	
	# Static Variables
	private static $dbConnection = null;
	
	
	# Static Methods
	public static function connect() {
		global $MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB;
		
		// If a connection exists, return it
		if(DBConn::$dbConnection != null)
			return DBConn::$dbConnection;
		
		// Create a connection
		DBConn::$dbConnection = new mysqli($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS,$MYSQL_DB);
		
		return DBConn::$dbConnection;
	}
	
	public static function clean($data, $cleanType = DBConn::CLEAN_INPUT) {
		if(is_string($data)) {
			$data = ltrim($data);
			$data = rtrim($data);
			//$data = htmlentities($data);
			$data = nl2br($data, true);
			
			$dbConn = DBConn::connect();
			$data = $dbConn->real_escape_string($data);
			
			if($cleanType == DBConn::CLEAN_VALIDATION)
				return $data;
			else
				return "'".$data."'";
		} elseif (is_array($data)) {
			foreach($data as $key => $val)
				$data[$key] = DBConn::clean($val, $cleanType);
			return $data;
		} else {
			return (int)$data;
		}
	}
}
?>