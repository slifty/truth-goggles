<?php
	require_once("../conf.php");

	$mysqli = DBConn::connect();
	$mysqli->query("delete from snippets");
	$mysqli->query("delete from verdicts");
	$mysqli->query("delete from claims");
	$mysqli->query("delete from corpus_items");
	$mysqli->query("delete from tokens");
	
	$study_claims = '[
		{
			"claim":"Modified cotton seeds have been designed to help the resulting plants withstand insect attacks, which allows farmers to avoid use of synthetic chemicals and other pesticides.",
			"ruling":"true",
			"explanation":"Pest resistance can be extremely costly, requiring farmers to spend a lot of time and money on pesticides.  Additionally, these pesticides bring about numerous hazards for farm workers themselves and can encroach on consumers\' health.  GMOs are helpful in curbing these problems because they help eliminate the need for pesticides in the harvesting process."
		},
		{
			"claim":"These consequences have the potential to influence not only the GMO itself, but also the natural environment in which that organism is allowed to proliferate.",
			"ruling":"true",
			"explanation":"A study showed that pollen from Bt corn, corn bioengineered to resist the European corn borer, a crop pest which can cause significant damage to crops, caused high mortality rates in monarch butterfly caterpillars. Although the killing of insects is the goal in pest resistance, it can flow into other unintended species."
		},
		{
			"claim":"The regulation is opt-in, meaning there is no centralized government body responsible for evaluating the products.",
			"ruling":"true",
			"explanation":"The FDA policy places responsibility on the producer or manufacturer to assure the safety of the food, explicitly relying on the producer/manufacturer to do so: \\"Ultimately, it is the responsibility of the producer of a new food to evaluate the safety of the food and assure that the safety requirement of section 402(a)(1) of the act is met.\\" So it is the company, not any independent scientific review, providing the research that is relied on to assert safety. FDA guidance to industry issued in 1997 covered voluntary \\"consultation procedures,\\" but still relied on the developer of the product to provide safety data."
		},
		{
			"claim":"We will not be able to meet the demand if the current trends do not change.",
			"ruling":"true",
			"explanation":"Although agricultural productivity has improved dramatically over the past 50 years, economists fear that these improvements have begun to wane at a time when food demand, driven by the larger number of people and the growing appetites of wealthier populations, is expected to rise between 70 and 100 percent by midcentury. In particular, the rapid increases in rice and wheat yields that helped feed the world for decades are showing signs of slowing down, and production of cereals will need to more than double by 2050 to keep up. If the trend continues, production might be insufficient to meet demand unless we start using significantly more land, fertilizer, and water."
		},
		{
			"claim":"So far it\'s turned out, for a number of reasons, to have been a somewhat empty promise.",
			"ruling":"true",
			"explanation":"It\'s not clear whether that boom in transgenic crops has led to increased food production or lower prices for consumers. Take corn, for example. In the United States, 76 percent of the crop is genetically modified to resist insects, and 85 percent can tolerate being sprayed with a weed killer. Such corn has, arguably, been a boon to farmers, reducing pesticide use and boosting yields. But little of U.S. corn production is used directly for human food; about 4 percent goes into high–fructose corn syrup and 1.8 percent to cereal and other foods. Genetically modified corn and soybeans are so profitable that U.S. farmers have begun substituting them for wheat: around 56 million acres of wheat were planted in 2012, down from 62 million in 2000. As supply fell, the price of a bushel of wheat rose to nearly $8 in 2012, from $2.50 in 2000."
		},
		{
			"claim":"There are also a wide variety of inedible applications for GMOs outside of food production, ranging from energy generators to providers of transplant organs.",
			"ruling":"true",
			"explanation":"Many industries stand to benefit from additional GMO research. For instance, a number of microorganisms are being considered as future clean fuel producers and biodegraders. In addition, genetically modified plants may someday be used to produce recombinant vaccines. In fact, the concept of an oral vaccine expressed in plants (fruits and vegetables) for direct consumption by individuals is being examined as a possible solution to the spread of disease in underdeveloped countries, one that would greatly reduce the costs associated with conducting large-scale vaccination campaigns. Work is currently underway to develop plantderived vaccine candidates in potatoes and lettuce for hepatitis B virus (HBV), enterotoxigenic Escherichia coli (ETEC), and Norwalk virus. Scientists are also looking into the production of other commercially valuable proteins in plants, such as spider silk protein and polymers that are used in surgery or tissue replacement (Ma et al., 2003). Genetically modified animals have even been used to grow transplant tissues and human transplant organs, a concept called xenotransplantation. "
		},

		
		{
			"claim":"sweeping new laws in a handful of Democratic-led states, including Maryland and New York",
			"ruling":"true",
			"explanation":"Colorado, Connecticut, Delaware, Maryland, and New York all enacted legislation to tighten gun control in 2013. Examples of new changes:<ol><li>A ban on assault-style weapons.</li><li>A 10-round limit for gun magazines.</li><li>Stiffer penalties for illegal possession and trafficking of guns</li><li>A requirement that gun owners to be fingerprinted and licensed by the state.</li><li>Training for many first-time gun owners.</li><li>A ban for some individuals with mental health issues from gun ownership.</li><li>A ban on the Internet sale of assault weapons.</li><li>Ammunition dealers are required to do background checks, similar to those for gun buyers.</li><li>Requires creation of a registry of assault weapons.</li><li>Stolen guns are required to be reported within 24 hours.</li><li>Required background checks for all gun sales, including by private sellers.</li></ol>"
		},
		{
			"claim":"more than two dozen states, most of them controlled by Republicans, moved in the opposite direction, expanding the rights of gun owners",
			"ruling":"true",
			"explanation":"Alabama, Arizona, Arkansas, Idaho, Kansas, Kentucky, Maine, Mississippi, North Dakota, South Dakota, Tennessee, Utah, Virginia, West Virginia, Wyoming all adopted changes.  Examples of new changes:<ol><li>Recognition of concealed permits from other states.</li><li>Guns can no longer be seized during a declared emergency.</li><li>Concealed weapons permits are no longer part of the public record.</li><li>A person currently not allowed to possess a firearm because of mental health reasons can petition the state for the right to bear arms.</li><li>Gun owners with concealed permits can keep guns in their vehicles in public or private parking lots.</li><li>Local school boards can train and arm employees and volunteers inside the classroom.</li><li>People who seek certain renewals of gun permits will no longer be fingerprinted.</li><li>Guns are now allowed in churches.</li><li>Federal gun laws will no longer be enforced, and any federal agent who tries to enforce federal laws faces arrest.</li><li>Police departments that conduct gun buyback programs to get guns off the street must sell the guns instead of destroying them.</li></ol>"
		},
		{
			"claim":"Since 1968, more Americans have died from gunfire than died in all the wars of the country\'s history",
			"ruling":"true",
			"explanation":"<img src=\'http://i.imgur.com/uCPyapB.png\'/>"
		},
		{
			"claim":"Over the past twenty years, the number of homicides committed with a firearm in the United States has decreased by nearly 40 percent",
			"ruling":"true",
			"explanation":"<img src=\'http://i.imgur.com/VkLJIYL.png\'/>"
		},
		{
			"claim":"the jurisdictions with the strictest gun control laws, almost without exception have the highest crime rates and the highest murder rates.",
			"ruling":"false",
			"explanation":"This point might hold for some places. However, there are multiple exceptions -- among cities, states and nations -- making this claim False. Stats from the FBI and United Nations alongside varied assessments of the \\"strictest\\" laws show that that America\'s two strictest cities (Washington, D.C., and Chicago) don\'t have the highest murder rates, that most U.S. states with \\"tight\\" gun laws fall toward the middle or bottom of the pack in crime and murder rates, and that of five nations regarded as having stringent laws, four had low murder rates and one had the world\'s 10th highest murder rate. That\'s not across-the-board proof, but it does scuttle Cruz\'s \\"almost without exception\\" statement."
		},
		{
			"claim":"40 percent of guns are purchased without a background check.",
			"ruling":"false",
			"explanation":"That figure is based on an analysis of a nearly two-decade-old survey of less than 300 people that essentially asked participants whether they thought the guns they had acquired — and not necessarily purchased — came from a federally licensed dealer. Even Joe Biden acknowledged that the statistic may not be accurate in a speech at a mayoral conference on Jan. 17. Biden prefaced his claim that by saying that \\"because of the lack of the ability of federal agencies to be able to even keep records, we can\'t say with absolute certainty what I\'m about to say is correct.\\""
		}
	]';



 



	$claims = json_decode($study_claims);

	$vetting_service = VettingService::getObjectByTitle("Truth Goggles");
	
	if($vetting_service == NULL) {
		$vetting_service = new VettingService();
		$vetting_service->setTitle("Truth Goggles");
		$vetting_service->setURL("http://truthgoggl.es");
		$vetting_service->save();
	}

	// Get result classes
	$RC_pants = ResultClass::getObjectByClass(ResultClass::CLASS_PANTS_ON_FIRE);
	$RC_false = ResultClass::getObjectByClass(ResultClass::CLASS_FALSE);
	$RC_barely_true = ResultClass::getObjectByClass(ResultClass::CLASS_BARELY_TRUE);
	$RC_half_true = ResultClass::getObjectByClass(ResultClass::CLASS_HALF_TRUE);
	$RC_mostly_true = ResultClass::getObjectByClass(ResultClass::CLASS_MOSTLY_TRUE);
	$RC_true = ResultClass::getObjectByClass(ResultClass::CLASS_TRUE);

	foreach($claims as $claim_object) {
		// We're checking to make sure the verdict is one we care about up front.

		$verdict = new Verdict();
		switch($claim_object->ruling) {
			case "true":
				$verdict->setResultClassID($RC_true->getItemID());
				break;
			case "false":
				$verdict->setResultClassID($RC_false->getItemID());
				break;
		}
		
		// Store the claim
		$claim = new Claim();
		$claim->setContent($claim_object->claim);
		$claim->save();
		
		// Store a snippet for the claim itself
		$snippet = new Snippet();
		$snippet->setClaimID($claim->getItemID());
		$snippet->setContent($claim_object->claim);
		$snippet->setContext($claim_object->claim);
		$snippet->save();
		
		$verdict->setClaimID($claim->getItemID());
		$verdict->setShortReason($claim_object->explanation);
		$verdict->setLongReason("");
		$verdict->setVettingServiceID($vetting_service->getItemID());
		$verdict->save();
	}

?>