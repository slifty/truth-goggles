<?php
	session_start();
?>
<html>
	<head>
		<style>
			#content { width: 900px; margin: auto; border: 1px solid black; }
			body { font-family: verdana; }
			input, label { cursor: pointer; font-size: 12px; }
			textarea { height: 100px; width: 400px; }
			ul {list-style: none; }
			ul li { margin-top: 30px; }
			ul li p { margin-top: 60px; font-weight: bold;}
			body {font-size: 16px;}
			th, td { font-size: 12px; font-weight: bold; }
			table.seven td { width: 100px;}
			table td input { margin: auto; display: block; }
			table.nine td { width: 75px;}
		</style>
	</head>
	<body>
		<div id="content">
			<form action="next" method="POST">
				<input type="hidden" name="treatment-id" value="<?php echo($_SESSION['previous']);?>">
				<ul>
					<li><p>Please rate the story you just read on the following:</p></li>
					<li>
						<table class="seven">
							<tr><th></th><th>Not at all</th><th colspan="5"></th><th>A lot</th></tr>
							<tr>
								<td>Believable</td>
								<td><input type="radio" name="story-believable" value="1"></td>
								<td><input type="radio" name="story-believable" value="2"></td>
								<td><input type="radio" name="story-believable" value="3"></td>
								<td><input type="radio" name="story-believable" value="4"></td>
								<td><input type="radio" name="story-believable" value="5"></td>
								<td><input type="radio" name="story-believable" value="6"></td>
								<td><input type="radio" name="story-believable" value="7"></td>
							</tr>
							<tr>
								<td>Thorough</td>
								<td><input type="radio" name="story-thorough" value="1"></td>
								<td><input type="radio" name="story-thorough" value="2"></td>
								<td><input type="radio" name="story-thorough" value="3"></td>
								<td><input type="radio" name="story-thorough" value="4"></td>
								<td><input type="radio" name="story-thorough" value="5"></td>
								<td><input type="radio" name="story-thorough" value="6"></td>
								<td><input type="radio" name="story-thorough" value="7"></td>
							</tr>
							<tr>
								<td>Accurate</td>
								<td><input type="radio" name="story-accurate" value="1"></td>
								<td><input type="radio" name="story-accurate" value="2"></td>
								<td><input type="radio" name="story-accurate" value="3"></td>
								<td><input type="radio" name="story-accurate" value="4"></td>
								<td><input type="radio" name="story-accurate" value="5"></td>
								<td><input type="radio" name="story-accurate" value="6"></td>
								<td><input type="radio" name="story-accurate" value="7"></td>
							</tr>
							<tr>
								<td>Factual</td>
								<td><input type="radio" name="story-factual" value="1"></td>
								<td><input type="radio" name="story-factual" value="2"></td>
								<td><input type="radio" name="story-factual" value="3"></td>
								<td><input type="radio" name="story-factual" value="4"></td>
								<td><input type="radio" name="story-factual" value="5"></td>
								<td><input type="radio" name="story-factual" value="6"></td>
								<td><input type="radio" name="story-factual" value="7"></td>
							</tr>
							<tr>
								<td>Biased</td>
								<td><input type="radio" name="story-biased" value="1"></td>
								<td><input type="radio" name="story-biased" value="2"></td>
								<td><input type="radio" name="story-biased" value="3"></td>
								<td><input type="radio" name="story-biased" value="4"></td>
								<td><input type="radio" name="story-biased" value="5"></td>
								<td><input type="radio" name="story-biased" value="6"></td>
								<td><input type="radio" name="story-biased" value="7"></td>
							</tr>
							<tr>
								<td>Interesting</td>
								<td><input type="radio" name="story-interesting" value="1"></td>
								<td><input type="radio" name="story-interesting" value="2"></td>
								<td><input type="radio" name="story-interesting" value="3"></td>
								<td><input type="radio" name="story-interesting" value="4"></td>
								<td><input type="radio" name="story-interesting" value="5"></td>
								<td><input type="radio" name="story-interesting" value="6"></td>
								<td><input type="radio" name="story-interesting" value="7"></td>
							</tr>
							<tr>
								<td>Informative</td>
								<td><input type="radio" name="story-informative" value="1"></td>
								<td><input type="radio" name="story-informative" value="2"></td>
								<td><input type="radio" name="story-informative" value="3"></td>
								<td><input type="radio" name="story-informative" value="4"></td>
								<td><input type="radio" name="story-informative" value="5"></td>
								<td><input type="radio" name="story-informative" value="6"></td>
								<td><input type="radio" name="story-informative" value="7"></td>
							</tr>
							<tr>
								<td>Important</td>
								<td><input type="radio" name="story-important" value="1"></td>
								<td><input type="radio" name="story-important" value="2"></td>
								<td><input type="radio" name="story-important" value="3"></td>
								<td><input type="radio" name="story-important" value="4"></td>
								<td><input type="radio" name="story-important" value="5"></td>
								<td><input type="radio" name="story-important" value="6"></td>
								<td><input type="radio" name="story-important" value="7"></td>
							</tr>
							<tr>
								<td>Serious</td>
								<td><input type="radio" name="story-serious" value="1"></td>
								<td><input type="radio" name="story-serious" value="2"></td>
								<td><input type="radio" name="story-serious" value="3"></td>
								<td><input type="radio" name="story-serious" value="4"></td>
								<td><input type="radio" name="story-serious" value="5"></td>
								<td><input type="radio" name="story-serious" value="6"></td>
								<td><input type="radio" name="story-serious" value="7"></td>
							</tr>
							<tr>
								<td>Good</td>
								<td><input type="radio" name="story-good" value="1"></td>
								<td><input type="radio" name="story-good" value="2"></td>
								<td><input type="radio" name="story-good" value="3"></td>
								<td><input type="radio" name="story-good" value="4"></td>
								<td><input type="radio" name="story-good" value="5"></td>
								<td><input type="radio" name="story-good" value="6"></td>
								<td><input type="radio" name="story-good" value="7"></td>
							</tr>
							<tr>
								<td>Positive</td>
								<td><input type="radio" name="story-positive" value="1"></td>
								<td><input type="radio" name="story-positive" value="2"></td>
								<td><input type="radio" name="story-positive" value="3"></td>
								<td><input type="radio" name="story-positive" value="4"></td>
								<td><input type="radio" name="story-positive" value="5"></td>
								<td><input type="radio" name="story-positive" value="6"></td>
								<td><input type="radio" name="story-positive" value="7"></td>
							</tr>
							<tr>
								<td>High Quality</td>
								<td><input type="radio" name="story-quality" value="1"></td>
								<td><input type="radio" name="story-quality" value="2"></td>
								<td><input type="radio" name="story-quality" value="3"></td>
								<td><input type="radio" name="story-quality" value="4"></td>
								<td><input type="radio" name="story-quality" value="5"></td>
								<td><input type="radio" name="story-quality" value="6"></td>
								<td><input type="radio" name="story-quality" value="7"></td>
							</tr>
						</table>
					</li>
					<li><p>Please rate the journalist who wrote the story you just read on the following:</p></li>
					<li>
						<table class="seven">
							<tr><th></th><th>Not at all</th><th colspan="5"></th><th>A lot</th></tr>
							<tr>
								<td>Believable</td>
								<td><input type="radio" name="journalist-believable" value="1"></td>
								<td><input type="radio" name="journalist-believable" value="2"></td>
								<td><input type="radio" name="journalist-believable" value="3"></td>
								<td><input type="radio" name="journalist-believable" value="4"></td>
								<td><input type="radio" name="journalist-believable" value="5"></td>
								<td><input type="radio" name="journalist-believable" value="6"></td>
								<td><input type="radio" name="journalist-believable" value="7"></td>
							</tr>
							<tr>
								<td>Thorough</td>
								<td><input type="radio" name="journalist-thorough" value="1"></td>
								<td><input type="radio" name="journalist-thorough" value="2"></td>
								<td><input type="radio" name="journalist-thorough" value="3"></td>
								<td><input type="radio" name="journalist-thorough" value="4"></td>
								<td><input type="radio" name="journalist-thorough" value="5"></td>
								<td><input type="radio" name="journalist-thorough" value="6"></td>
								<td><input type="radio" name="journalist-thorough" value="7"></td>
							</tr>
							<tr>
								<td>Accurate</td>
								<td><input type="radio" name="journalist-accurate" value="1"></td>
								<td><input type="radio" name="journalist-accurate" value="2"></td>
								<td><input type="radio" name="journalist-accurate" value="3"></td>
								<td><input type="radio" name="journalist-accurate" value="4"></td>
								<td><input type="radio" name="journalist-accurate" value="5"></td>
								<td><input type="radio" name="journalist-accurate" value="6"></td>
								<td><input type="radio" name="journalist-accurate" value="7"></td>
							</tr>
							<tr>
								<td>Factual</td>
								<td><input type="radio" name="journalist-factual" value="1"></td>
								<td><input type="radio" name="journalist-factual" value="2"></td>
								<td><input type="radio" name="journalist-factual" value="3"></td>
								<td><input type="radio" name="journalist-factual" value="4"></td>
								<td><input type="radio" name="journalist-factual" value="5"></td>
								<td><input type="radio" name="journalist-factual" value="6"></td>
								<td><input type="radio" name="journalist-factual" value="7"></td>
							</tr>
							<tr>
								<td>Biased</td>
								<td><input type="radio" name="journalist-biased" value="1"></td>
								<td><input type="radio" name="journalist-biased" value="2"></td>
								<td><input type="radio" name="journalist-biased" value="3"></td>
								<td><input type="radio" name="journalist-biased" value="4"></td>
								<td><input type="radio" name="journalist-biased" value="5"></td>
								<td><input type="radio" name="journalist-biased" value="6"></td>
								<td><input type="radio" name="journalist-biased" value="7"></td>
							</tr>
							<tr>
								<td>Good</td>
								<td><input type="radio" name="journalist-good" value="1"></td>
								<td><input type="radio" name="journalist-good" value="2"></td>
								<td><input type="radio" name="journalist-good" value="3"></td>
								<td><input type="radio" name="journalist-good" value="4"></td>
								<td><input type="radio" name="journalist-good" value="5"></td>
								<td><input type="radio" name="journalist-good" value="6"></td>
								<td><input type="radio" name="journalist-good" value="7"></td>
							</tr>
							<tr>
								<td>Professional</td>
								<td><input type="radio" name="journalist-professional" value="1"></td>
								<td><input type="radio" name="journalist-professional" value="2"></td>
								<td><input type="radio" name="journalist-professional" value="3"></td>
								<td><input type="radio" name="journalist-professional" value="4"></td>
								<td><input type="radio" name="journalist-professional" value="5"></td>
								<td><input type="radio" name="journalist-professional" value="6"></td>
								<td><input type="radio" name="journalist-professional" value="7"></td>
							</tr>
							<tr>
								<td>Careless</td>
								<td><input type="radio" name="journalist-careless" value="1"></td>
								<td><input type="radio" name="journalist-careless" value="2"></td>
								<td><input type="radio" name="journalist-careless" value="3"></td>
								<td><input type="radio" name="journalist-careless" value="4"></td>
								<td><input type="radio" name="journalist-careless" value="5"></td>
								<td><input type="radio" name="journalist-careless" value="6"></td>
								<td><input type="radio" name="journalist-careless" value="7"></td>
							</tr>
						</table>
					</li>
					<li><p>How positive was the article about <?php echo($_SESSION['previous_topic']);?>?</p></li>
					<li>
						<table class="seven">
							<tr><th></th><th>Not at all</th><th colspan="5"></th><th>A lot</th></tr>
							<tr>
								<td></td>
								<td><input type="radio" name="article-positive-2" value="1"></td>
								<td><input type="radio" name="article-positive-2" value="2"></td>
								<td><input type="radio" name="article-positive-2" value="3"></td>
								<td><input type="radio" name="article-positive-2" value="4"></td>
								<td><input type="radio" name="article-positive-2" value="5"></td>
								<td><input type="radio" name="article-positive-2" value="6"></td>
								<td><input type="radio" name="article-positive-2" value="7"></td>
							</tr>
						</table>
					</li>
					<li><p>Of the information about <?php echo($_SESSION['previous_topic']);?>, what percentage would you say was positive?</p></li>
					<li><select name="article-positive-pct">
						<option value=""></option>
						<?php 
							for($x = 0; $x<=100; $x++) {
								?>
								<option value="<?php echo($x);?>"><?php echo($x);?>%</option>
								<?php
							}
						?>
					</select></li>
					<li><p>Overall, would say that the person who wrote this article was strictly neutral, or was he/she biased in favor of one party?</p></li>
					<li>
						<table class="nine">
							<tr><th>Strongly in favor<br/>of Democrats</th><th colspan="7"></th><th>Strongly in favor<br/>of Republicans</th></tr>
							<tr>
								<td><input type="radio" name="article-lean" value="-4"></td>
								<td><input type="radio" name="article-lean" value="-3"></td>
								<td><input type="radio" name="article-lean" value="-2"></td>
								<td><input type="radio" name="article-lean" value="-1"></td>
								<td><input type="radio" name="article-lean" value="0"></td>
								<td><input type="radio" name="article-lean" value="1"></td>
								<td><input type="radio" name="article-lean" value="2"></td>
								<td><input type="radio" name="article-lean" value="3"></td>
								<td><input type="radio" name="article-lean" value="4"></td>
							</tr>
						</table>
					</li>
					<li><p>Please rate how you felt as you read the story:</p></li>
					<li>
						<table class="seven">
							<tr><th></th><th>Not at all</th><th colspan="5"></th><th>A lot</th></tr>
							<tr>
								<td>Angry</td>
								<td><input type="radio" name="feel-angry" value="1"></td>
								<td><input type="radio" name="feel-angry" value="2"></td>
								<td><input type="radio" name="feel-angry" value="3"></td>
								<td><input type="radio" name="feel-angry" value="4"></td>
								<td><input type="radio" name="feel-angry" value="5"></td>
								<td><input type="radio" name="feel-angry" value="6"></td>
								<td><input type="radio" name="feel-angry" value="7"></td>
							</tr>
							<tr>
								<td>Irritated</td>
								<td><input type="radio" name="feel-irritated" value="1"></td>
								<td><input type="radio" name="feel-irritated" value="2"></td>
								<td><input type="radio" name="feel-irritated" value="3"></td>
								<td><input type="radio" name="feel-irritated" value="4"></td>
								<td><input type="radio" name="feel-irritated" value="5"></td>
								<td><input type="radio" name="feel-irritated" value="6"></td>
								<td><input type="radio" name="feel-irritated" value="7"></td>
							</tr>
							<tr>
								<td>Aggravated</td>
								<td><input type="radio" name="feel-aggravated" value="1"></td>
								<td><input type="radio" name="feel-aggravated" value="2"></td>
								<td><input type="radio" name="feel-aggravated" value="3"></td>
								<td><input type="radio" name="feel-aggravated" value="4"></td>
								<td><input type="radio" name="feel-aggravated" value="5"></td>
								<td><input type="radio" name="feel-aggravated" value="6"></td>
								<td><input type="radio" name="feel-aggravated" value="7"></td>
							</tr>
							<tr>
								<td>Mad</td>
								<td><input type="radio" name="feel-mad" value="1"></td>
								<td><input type="radio" name="feel-mad" value="2"></td>
								<td><input type="radio" name="feel-mad" value="3"></td>
								<td><input type="radio" name="feel-mad" value="4"></td>
								<td><input type="radio" name="feel-mad" value="5"></td>
								<td><input type="radio" name="feel-mad" value="6"></td>
								<td><input type="radio" name="feel-mad" value="7"></td>
							</tr>
							<tr>
								<td>Fearful</td>
								<td><input type="radio" name="feel-fearful" value="1"></td>
								<td><input type="radio" name="feel-fearful" value="2"></td>
								<td><input type="radio" name="feel-fearful" value="3"></td>
								<td><input type="radio" name="feel-fearful" value="4"></td>
								<td><input type="radio" name="feel-fearful" value="5"></td>
								<td><input type="radio" name="feel-fearful" value="6"></td>
								<td><input type="radio" name="feel-fearful" value="7"></td>
							</tr>
							<tr>
								<td>Afraid</td>
								<td><input type="radio" name="feel-afraid" value="1"></td>
								<td><input type="radio" name="feel-afraid" value="2"></td>
								<td><input type="radio" name="feel-afraid" value="3"></td>
								<td><input type="radio" name="feel-afraid" value="4"></td>
								<td><input type="radio" name="feel-afraid" value="5"></td>
								<td><input type="radio" name="feel-afraid" value="6"></td>
								<td><input type="radio" name="feel-afraid" value="7"></td>
							</tr>
							<tr>
								<td>Scared</td>
								<td><input type="radio" name="feel-scared" value="1"></td>
								<td><input type="radio" name="feel-scared" value="2"></td>
								<td><input type="radio" name="feel-scared" value="3"></td>
								<td><input type="radio" name="feel-scared" value="4"></td>
								<td><input type="radio" name="feel-scared" value="5"></td>
								<td><input type="radio" name="feel-scared" value="6"></td>
								<td><input type="radio" name="feel-scared" value="7"></td>
							</tr>
							<tr>
								<td>Upset</td>
								<td><input type="radio" name="feel-upset" value="1"></td>
								<td><input type="radio" name="feel-upset" value="2"></td>
								<td><input type="radio" name="feel-upset" value="3"></td>
								<td><input type="radio" name="feel-upset" value="4"></td>
								<td><input type="radio" name="feel-upset" value="5"></td>
								<td><input type="radio" name="feel-upset" value="6"></td>
								<td><input type="radio" name="feel-upset" value="7"></td>
							</tr>
							<tr>
								<td>Elated</td>
								<td><input type="radio" name="feel-elated" value="1"></td>
								<td><input type="radio" name="feel-elated" value="2"></td>
								<td><input type="radio" name="feel-elated" value="3"></td>
								<td><input type="radio" name="feel-elated" value="4"></td>
								<td><input type="radio" name="feel-elated" value="5"></td>
								<td><input type="radio" name="feel-elated" value="6"></td>
								<td><input type="radio" name="feel-elated" value="7"></td>
							</tr>
							<tr>
								<td>Happy</td>
								<td><input type="radio" name="feel-happy" value="1"></td>
								<td><input type="radio" name="feel-happy" value="2"></td>
								<td><input type="radio" name="feel-happy" value="3"></td>
								<td><input type="radio" name="feel-happy" value="4"></td>
								<td><input type="radio" name="feel-happy" value="5"></td>
								<td><input type="radio" name="feel-happy" value="6"></td>
								<td><input type="radio" name="feel-happy" value="7"></td>
							</tr>
							<tr>
								<td>Joyful</td>
								<td><input type="radio" name="feel-joyful" value="1"></td>
								<td><input type="radio" name="feel-joyful" value="2"></td>
								<td><input type="radio" name="feel-joyful" value="3"></td>
								<td><input type="radio" name="feel-joyful" value="4"></td>
								<td><input type="radio" name="feel-joyful" value="5"></td>
								<td><input type="radio" name="feel-joyful" value="6"></td>
								<td><input type="radio" name="feel-joyful" value="7"></td>
							</tr>
							<tr>
								<td>Cheerful</td>
								<td><input type="radio" name="feel-cheerful" value="1"></td>
								<td><input type="radio" name="feel-cheerful" value="2"></td>
								<td><input type="radio" name="feel-cheerful" value="3"></td>
								<td><input type="radio" name="feel-cheerful" value="4"></td>
								<td><input type="radio" name="feel-cheerful" value="5"></td>
								<td><input type="radio" name="feel-cheerful" value="6"></td>
								<td><input type="radio" name="feel-cheerful" value="7"></td>
							</tr>
							<tr>
								<td>Sad</td>
								<td><input type="radio" name="feel-sad" value="1"></td>
								<td><input type="radio" name="feel-sad" value="2"></td>
								<td><input type="radio" name="feel-sad" value="3"></td>
								<td><input type="radio" name="feel-sad" value="4"></td>
								<td><input type="radio" name="feel-sad" value="5"></td>
								<td><input type="radio" name="feel-sad" value="6"></td>
								<td><input type="radio" name="feel-sad" value="7"></td>
							</tr>
							<tr>
								<td>Dreary</td>
								<td><input type="radio" name="feel-dreary" value="1"></td>
								<td><input type="radio" name="feel-dreary" value="2"></td>
								<td><input type="radio" name="feel-dreary" value="3"></td>
								<td><input type="radio" name="feel-dreary" value="4"></td>
								<td><input type="radio" name="feel-dreary" value="5"></td>
								<td><input type="radio" name="feel-dreary" value="6"></td>
								<td><input type="radio" name="feel-dreary" value="7"></td>
							</tr>
							<tr>
								<td>Dismal</td>
								<td><input type="radio" name="feel-dismal" value="1"></td>
								<td><input type="radio" name="feel-dismal" value="2"></td>
								<td><input type="radio" name="feel-dismal" value="3"></td>
								<td><input type="radio" name="feel-dismal" value="4"></td>
								<td><input type="radio" name="feel-dismal" value="5"></td>
								<td><input type="radio" name="feel-dismal" value="6"></td>
								<td><input type="radio" name="feel-dismal" value="7"></td>
							</tr>
						</table>
					</li>
					<li><p>What would you say is the structure of the story you just read (select one)?</p></li>
					<li>
						<ul>
							<li><input type="radio" name="recall" id="recall-1" value="1"><label for="recall-1">Story with clickable links to information</label></li>
							<li><input type="radio" name="recall" id="recall-2" value="2"><label for="recall-2">Story with no clickable links but information added at the end</label></li>
							<li><input type="radio" name="recall" id="recall-3" value="3"><label for="recall-3">Story with no extra information</label></li>
						</ul>
					</li>
					<li><p>What, if anything, would you have liked to see in the story that it did not contain?</p></li>
					<li><textarea name="feedback" id="story-feedback"></textarea></li>
					<li><input type="submit"></li>
				</ul>
			</form>
		</div>
	</body>
</html>