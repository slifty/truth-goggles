<?php
// Set Time Zone
	

// ReCaptcha Settings
	global $RECAPTCHA_PUBLIC_KEY, $RECAPTCHA_PRIVATE_KEY;
	$RECAPTCHA_PUBLIC_KEY = "";
	$RECAPTCHA_PRIVATE_KEY = "";
	
// DB Connection Settings
	global $MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB;
	$MYSQL_HOST = "";
	$MYSQL_USER = "";
	$MYSQL_PASS = "";
	$MYSQL_DB = "";

// Memcache Connection Settings
	global $MEMCACHE_HOST, $MEMCACHE_PORT;
	$MEMCACHE_HOST = "";
	$MEMCACHE_PORT = 11211;

// Bootstrap Settings
	global $BASE_DIRECTORY;
	# If you are hosting Truth Goggles in your base directory
	$BASE_DIRECTORY = "/";
	
	# If you are hosting Truth Goggles in a subdirectory (replace [subdirectory path] with the appropriate path)
	# $ROOT = "/[subdirectory path]/";
	
// Thermonuclear Disaster Prevention
	global $ALLOW_THERMONUCLEAR_DISASTER;
	$ALLOW_THERMONUCLEAR_DISASTER = false;

// Include everything
include_once("includes/models/Argument.php");
include_once("includes/models/Contribution.php");
require_once("includes/models/DBConn.php");
require_once("includes/models/Evidence.php");
require_once("includes/models/Layer.php");
require_once("includes/models/Question.php");
require_once("includes/models/Statement.php");
require_once("includes/lib/readability.php");
require_once("includes/engine/helpers/helpers.php");

	
?>