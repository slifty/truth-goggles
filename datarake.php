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

$claim2 = new Claim();
$claim2->setContent("Social Security recipients are not receiving a cost-of-living adjustment because the government");
$claim2->save();


// Add in some temporary snippets
$snippet1 = new Snippet();
$snippet1->setClaimID($claim1->getItemID());
$snippet1->setURL("http://kaystreet.wordpress.com/2010/11/27/");
$snippet1->setContext("While advising his Fox News viewers to talk about inflation at their Thanksgiving dinners, Glenn Beck falsely claimed that the government removed food and energy prices from its measure of inflation to hide rising prices, that a survey showed economists are “worried” about inflation, and that Social Security recipients are not receiving a cost-of-living adjustment because the government “changed the calculation.”");
$snippet1->setContent("the government removed food and energy prices from its measure of inflation to hide rising prices");
$snippet1->save();


// Add in some temporary verdicts
$verdict1 = new Verdict();
$verdict1->setClaimID($claim1->getItemID());
$verdict1->setResultClassID($RC_mostlyFalse->getItemID());
$verdict1->save();

$verdict1 = new Verdict();
$verdict1->setClaimID($claim2->getItemID());
$verdict1->setResultClassID($RC_true->getItemID());
$verdict1->save();


// Add in some temporary corpus items
$corpus1 = new CorpusItem();
$corpus1->setClaimID($claim1->getItemID());
$corpus1->setContent("On a Thanksgiving broadcast on Nov. 23, Glenn Beck of the Fox News Channel declared that the U.S. government 'calculates inflation without adding in the price of food and energy.' We find this claim to be mostly false. 

The U.S. government does have several different indices it uses to measure inflation over time. One of them, the 'core' inflation rate, does exclude food and energy because historically they tend to hop up and down over short time spans. One could therefore argue that Beck's statement is accurate in a narrow sense -- since the government actually calculates inflation both with and without 'adding in the price of food and energy.' 

But a closer reading of Beck's broadcast makes it impossible to justify his statement on this narrow ground. Beck's whole point is that the government has deliberately changed its method of calculating inflation so it can hide how high prices have climbed. That claim has no basis. The government has not changed its practices, and inflation remains low by any yardstick -- with or without food and energy included. 

Here's a lengthier transcript from Beck's broadcast:
'Inflation isn't even computed like it used to be computed. The government figured it out. The government realized that people could recognize how bad things actually were so they changed how we calculate it. So in other words, the TV could say, there's no inflation, and you'd be going, I'm broke, how's that happening? Now they calculate inflation without adding in the price of food and energy. Oh, other than those going up, we're set!...Hey grandma, things are getting more expensive. Ask this question: is your Social Security check going up to cover the cost next year? The answer is no. Why? Because they changed the calculation!'
Here's where we can really see how Beck's argument fails to pass the truth test. 

It's false to say 'Inflation isn't even computed like it used to be computed.' The Consumer Price Index that includes energy and food, known as the CPI-W, has not changed. Though the CPI-W is higher than the 'core' CPI (the one that excludes food and energy), it's still running at historic lows, as this November 19 report from the New York Times indicates: 'The latest figures, released this week, showed that overall inflation in consumer prices was 1.2 percent in the 12 months through October, while the core inflation rate — excluding food and energy — rose just 0.6 percent.' 

So Beck's larger conceit that the government is somehow using the core inflation measurement to conceal much broader inflation is false, too. If the core inflation measure didn't exist, we'd all still be in substantially the same economic position. 

Finally, Beck's statement about Social Security is untrue as well, since Social Security uses the CPI-W, not the core rate, to calculate its increases. 'Grandma' will, most likely, get a small Social Security increase to match the small jump in prices. 

Thanks to Peggy Kruse, Deb Duncan, Luis Prado, and Howard Gross for their thoughtful observations in this investigation -- and for posting links to factual evidence that helped us reach our verdict.");
$corpus1->save();
?>