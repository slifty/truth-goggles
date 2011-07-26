<?php

set_include_path("/");
require_once("models/DBConn.php");
require_once("models/Snippet.php");
require_once("models/Verdict.php");
require_once("models/Claim.php");
require_once("models/ResultClass.php");

// Clean out the old data
$mysqli = DBConn::connect();
$mysqli->query("delete from snippets");
$mysqli->query("delete from verdicts");
$mysqli->query("delete from claims");
$mysqli->query("delete from result_classes");

// Add in the result classes
$RC_false = new ResultClass();
$RC_false->setTitle("False");
$RC_false->setDescription("This claim is very inaccurate.  Beware!");
$RC_false->setClass("false");
$RC_false->save();

$RC_mostlyFalse = new ResultClass();
$RC_mostlyFalse->setTitle("Mostly False");
$RC_mostlyFalse->setDescription("This claim has some truth to it, but for the most part it is false.");
$RC_mostlyFalse->setClass("mostlyFalse");
$RC_mostlyFalse->save();

$RC_mostlyTrue = new ResultClass();
$RC_mostlyTrue->setTitle("Mostly True");
$RC_mostlyTrue->setDescription("This claim has some errors, but for the most part it is true.");
$RC_mostlyTrue->setClass("mostlyTrue");
$RC_mostlyTrue->save();

$RC_true = new ResultClass();
$RC_true->setTitle("True");
$RC_true->setDescription("This claim is very accurate.");
$RC_true->setClass("true");
$RC_true->save();


// Add in some temporary claims
$claim1 = new Claim();
$claim1->setContent("The U.S. government calculates inflation without adding in the price of food and energy");
$claim1->save();

// Add in some temporary snippets
$snippet1 = new Snippet();
$snippet1->setClaimID($claim1->getItemID());
$snippet1->setURL("http://kaystreet.wordpress.com/2010/11/27/");
$snippet1->setContext("While advising his Fox News viewers to talk about inflation at their Thanksgiving dinners, Glenn Beck falsely claimed that the government removed food and energy prices from its measure of inflation to hide rising prices, that a survey showed economists are “worried” about inflation, and that Social Security recipients are not receiving a cost-of-living adjustment because the government “changed the calculation.”");
$snippet1->setContent("the government removed food and energy prices from its measure of inflation to hide rising prices");
$snippet1->save();

$snippet1 = new Snippet();
$snippet1->setClaimID($claim1->getItemID());
$snippet1->setURL("http://kaystreet.wordpress.com/2010/11/27/");
$snippet1->setContext("Beck: Government removed “food and energy” from inflation estimate so that people wouldn’t “recognize how bad things actually were.” From the November 22 edition of Fox News’ Glenn Beck:");
$snippet1->setContent("Government removed “food and energy” from inflation estimate so that people wouldn’t “recognize how bad things actually were.”");
$snippet1->save();


// Add in some temporary verdicts
$verdict1 = new Verdict();
$verdict1->setClaimID($claim1->getItemID());
$verdict1->setResultClassID($RC_mostlyFalse->getItemID());
$verdict1->save();

?>