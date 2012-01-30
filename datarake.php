<?php

set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once("models/DBConn.php");
require_once("models/Snippet.php");
require_once("models/Verdict.php");
require_once("models/Claim.php");
require_once("models/ResultClass.php");
require_once("models/CorpusItem.php");

// Clean out the old data
$mysqli = DBConn::connect();
$mysqli->query("delete from snippets");
$mysqli->query("delete from verdicts");
$mysqli->query("delete from claims");
$mysqli->query("delete from result_classes");
$mysqli->query("delete from corpus_items");

// Add in the result classes
$RC_pants = new ResultClass();
$RC_pants->setTitle("Pants on Fire");
$RC_pants->setDescription("A blatant lie.");
$RC_pants->setClass(ResultClass::CLASS_PANTS_ON_FIRE);
$RC_pants->save();

$RC_false = new ResultClass();
$RC_false->setTitle("False");
$RC_false->setDescription("This claim is very inaccurate.  Beware!");
$RC_false->setClass(ResultClass::CLASS_FALSE);
$RC_false->save();

$RC_mostlyFalse = new ResultClass();
$RC_mostlyFalse->setTitle("Barely True");
$RC_mostlyFalse->setDescription("This is barely true at all.");
$RC_mostlyFalse->setClass(ResultClass::CLASS_BARELY_TRUE);
$RC_mostlyFalse->save();

$RC_halfTrue = new ResultClass();
$RC_halfTrue->setTitle("Half True");
$RC_halfTrue->setDescription("This is partly true and partly false.");
$RC_halfTrue->setClass(ResultClass::CLASS_HALF_TRUE);
$RC_halfTrue->save();

$RC_mostlyTrue = new ResultClass();
$RC_mostlyTrue->setTitle("Mostly True");
$RC_mostlyTrue->setDescription("This claim has some errors, but for the most part it is true.");
$RC_mostlyTrue->setClass(ResultClass::CLASS_MOSTLY_TRUE);
$RC_mostlyTrue->save();

$RC_true = new ResultClass();
$RC_true->setTitle("True");
$RC_true->setDescription("This claim is very accurate.");
$RC_true->setClass(ResultClass::CLASS_TRUE);
$RC_true->save();

?>