<?php
###
# Info:
#  Last Updated 2011
#  Daniel Schultz
#
# Comments:
###
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
		DBConn::$dbConnection->query("SET CHARACTER SET 'utf8'") or print($mysqli->error); 
		
		return DBConn::$dbConnection;
	}
	
	public static function clean($data, $cleanType = DBConn::CLEAN_INPUT) {
		if(is_string($data)) {
			$data = ltrim($data);
			$data = rtrim($data);
			
			// Get rid of backslashes
			$data = str_replace('\\','\\\\',$data);
			
			// Damn pesky carriage returns...
			$data = str_replace("\r\n", "\n", $data);
			$data = str_replace("\r", "\n", $data);
			
			// JSON requires some characters be escaped
			$data = str_replace("\n", "\\n", $data);
			$data = str_replace("\t", "\\t", $data);
			$data = str_replace('"','\\"',$data);
			
			if($cleanType == DBConn::CLEAN_VALIDATION)
				return $data;
			else
				return '"'.$data.'"';
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