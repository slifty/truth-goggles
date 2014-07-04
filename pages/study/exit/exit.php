<html>
	<head></head>
	<body>
		<form action="next" method="POST">
			<ul>
				<li><p>When it comes to politics do you usually think of yourself as extremely liberal, liberal, slightly liberal, moderate or middle of the road, slightly conservative, extremely  conservative? Please place yourself on this scale.</p></li>
				<li><ul>
					<li><input type="radio" name="ideology" id="ideology-1" value="1"/><label class="radio" for="ideology-1">Extremely liberal</label></li>
					<li><input type="radio" name="ideology" id="ideology-2" value="2"/><label class="radio" for="ideology-2">Liberal</label></li>
					<li><input type="radio" name="ideology" id="ideology-3" value="3"/><label class="radio" for="ideology-3">Slightly liberal</label></li>
					<li><input type="radio" name="ideology" id="ideology-4" value="4"/><label class="radio" for="ideology-4">Moderate</label></li>
					<li><input type="radio" name="ideology" id="ideology-5" value="5"/><label class="radio" for="ideology-5">Slightly conservative</label></li>
					<li><input type="radio" name="ideology" id="ideology-6" value="6"/><label class="radio" for="ideology-6">Conservative</label></li>
					<li><input type="radio" name="ideology" id="ideology-7" value="7"/><label class="radio" for="ideology-7">Extremely conservative</label></li>
				</ul></li>
				<li><p>Please tell us your age</p></li>
				<li><input type="text" id="age" name="age" /></li>
				<li><p>Please tell us your gender</p></li>
				<li><select id="gender" name="gender">
					<option>Select one...</option>
					<option value="1">Male</option>
					<option value="2">Female</option>
					<option value="3">Other</option>
				</select></li>
				<li><p>Please indicate your highest level of education</p></li>
				<li><select id="education" name="education">
					<option>Select one...</option>
					<option value="1">some high school</option>
					<option value="2">high school/GED</option>
					<option value="3">some college</option>
					<option value="4">college</option>
					<option value="5">some graduate school</option>
					<option value="6">graduate school</option>
				</select></li>
				<li><p>Please specify your ethnicity</p></li>
				<li><select id="ethnicity" name="ethnicity">
					<option>Select one...</option>
					<option value="1">White</option>
					<option value="2">Hispanic or Latino</option>
					<option value="3">Black or African American</option>
					<option value="4">Native American or American Indian</option>
					<option value="5">Asian / Pacific Islander</option>
					<option value="6">Other</option>
				</select></li>
				<li><p>Please indicate your yearly household income</p></li>
				<li><select id="income" name="income">
					<option>Select one...</option>
					<option value="1">Under 10,001</option>
					<option value="2">10,001-20,000</option>
					<option value="3">20,001-40,000</option>
					<option value="4">40,001-60,000</option>
					<option value="5">60,001-80,000</option>
					<option value="6">80,001-100,000</option>
					<option value="7">Over 100,001</option>
				</select></li>
				<li><input type="submit"/></li>
			</ul>
		</form>
	</body>
</html>