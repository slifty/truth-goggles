$(function() {
	var BASE = (typeof BASE_PATH == "undefined")?"":BASE_PATH;

	var source_text = ""; // The raw text that we used to create a layer.
	var source_url = ""; // The URL (if applicable) that we are creating a layer for

	var processed_words = []; // The list of words in the article
	var contributions = []; // The list of phrases that are being added to the layer
	var layer_id = 0; // The ID of the layer that has been created
	var next_contribution_id = 0; // The temporary local ID of the next contribution

	var questions = [
	  "Are you sure that's true?",
	  "Who is saying this?",
	  "Who else is involved?",
	  "Who benefits from this?",
	  "What other evidence should I consider?",
	  "What does this change?",
	  "What does this mean for me?",
	  "When did this happen?",
	  "How often does this happen?",
	  "Where does this have an impact?",
	  "Where did this happen?",
	  "Why did this happen?",
	  "Why should I care?",
	];


	/* Switch frames in the process */ 
	function switchFrame(id) {
		var $frame = $("#frame-" + id);
		$(".frame").hide();
		$frame.show();
		ga('send', 'event', 'annotation', 'changeFrame', id);
		console.log(id)
	}


	// Load in content from a JSON content list with paragraphs and sentences in individual strings.
	// Example:
	// [["Sentence 1", "Sentence 2"],["Sentence 3, Paragraph 2"]]
	function loadContent(content) {

		// Set up the content area
		var $content = $("#content");
		$content.empty();

		// Render the words
		var word_id = 0;
		for(var x in content) {
			var paragraph = content[x];
			var $paragraph = $("<p>")
				.appendTo($content);

			for(var y in paragraph) {
				var sentence = paragraph[y];
				var words = sentence.split(" ");
				for(var z in words) {
					var word = words[z];
					processed_words[word_id] = word;
					var $word = $("<span>")
						.addClass("word")
						.attr("id", "word-" + word_id)
						.text(word + " ")
						.data("word_id", word_id)
						.data("contribution_id", "")
						.appendTo($paragraph);
					word_id++;
				}
			}
		}

		// Prepare highlight tracking variables
		var isClicking = false;
		var start = -1;
		var end = -1;
		$content.mousedown(function() {
			start = -1;
			end = -1;
			isClicking = true;
			return false;
		});

		// Highlights are processed when the mouse goes up
		$(document).mouseup(function() {
			isClicking = false;
			start = -1;
			end = -1;
			processHighlight();
		});

		// Highlights are tracked and invoiked as the mouse moves
		$(".word")
			.mousemove(function() {
				if(!isClicking) return;

				var word_id = $(this).data("word_id");
				if(start == -1)
					start = word_id;
				else if(end != word_id) {
					end = word_id;
					$(".highlighted").removeClass("highlighted");
					for(var x = Math.min(start,end); x <= Math.max(start,end); x++)
						$("#word-" + x).addClass("highlighted");
				}
			})
			.mousedown(function() {
				$(this).addClass("highlighted");
			})
			.click(function() {
				var word_id = $(this).data("word_id");
				var contribution_id = getContributionId(word_id);
				if(contribution_id !== "") {
					var $words = $(".contribution-" + contribution_id);
					var start = -1;
					var end = -1;
					$words.each(function(i,el) {
						var word_id = $(el).data("word_id");
						if(start == -1)
							start = word_id;
						end = word_id;
					})
					createLayer(start, end);
				}
			})
	}

	function processHighlight() {
		var $highlights = $(".highlighted");
		$highlights.removeClass("highlighted");

		// Find the first valid word block that is not already in a contribution
		var start = -1;
		var end = -1;
		$highlights.each(function(i,el) {
			var $word = $(el)
			var word_id = $word.data("word_id");
			if($word.hasClass("selected")) {
				// This word is already used in a contribution
				if(start == -1)
					return true;
				else
					return false;
			} else {
				if(start == -1)
					start = word_id;
				end = word_id;
			}
		})

		if(start == -1 || end == -1)
			return;

		// Mark the word block
		for(var x = Math.min(start,end); x <= Math.max(start,end); x++)
			$("#word-" + x).addClass("selected");

		// Load the word block
		var sentence = getSentence(start, end);

		createLayer(start, end);
	}

	function createLayer(start, end) {
		var sentence = getSentence(start,end);
		var contribution_id = getContributionId(start);
		var contribution = (contribution_id === "")?false:contributions[contribution_id];

		var $background = $("<div>")
			.attr("id", "promptBackground")
			.hide()
			.click(function(e) { e.stopPropagation(); })
			.appendTo($("body"));

		var $prompt = $("<div>")
			.attr("id","prompt")
			.hide()
			.appendTo($("body"));

		var $promptSentence = $("<div>")
			.attr("id", "promptSentence")
			.text('"' + sentence + '"')
			.appendTo($prompt);

		var $promptQuestionTitle = $("<h3>")
			.attr("id", "promptQuestionTitle")
			.text("What should people ask themselves when they see this?")
			.appendTo($prompt);

		var $promptQuestionSelect = $("<select>")
			.attr("id", "promptQuestionSelect")
			.appendTo($prompt)
			.change(function() {
				var val = $(this).val();
				if(val == "other") {
					$promptQuestionText.show()
						.focus();
				} else {
					$promptQuestionText.hide();
				}
			});

		for(var x in questions) {
			var question = questions[x];
			var $question = $("<option>")
				.attr("value", question)
				.text(question)
				.appendTo($promptQuestionSelect);
		}

		$promptQuestionOther = $("<option>")
			.attr("value", "other")
			.text("other...")
			.appendTo($promptQuestionSelect);

		$promptQuestionText = $("<input>")
			.attr("id", "promptQuestionText")
			.attr("type","text")
			.hide()
			.appendTo($prompt);

		if(contribution) {
			if($promptQuestionSelect.find('option[value="' + contribution.question.replace('"','') + '"]').length > 0)
				$promptQuestionSelect.val(contribution.question);
			else {
				$promptQuestionSelect.val("other").change();
				$promptQuestionText.val(contribution.question);
			}
		}
		$promptAnswerTitle = $("<h3>")
			.attr("id", "promptAnswerTitle")
			.text("Do you have any answers?")
			.appendTo($prompt);

		$promptShortAnswerLabel = $("<label>")
			.attr("for", "promptShortAnswer")
			.text("Short explanation:")
			.appendTo($prompt);

		$promptShortAnswer = $("<input>")
			.attr("id", "promptShortAnswer")
			.attr("type", "text")
			.val(contribution?contribution.summary:"")
			.appendTo($prompt)

		$promptLongAnswerLabel = $("<label>")
			.attr("for", "promptLongAnswer")
			.text("Long explanation:")
			.appendTo($prompt);

		$promptLongAnswer = $("<textarea>")
			.attr("id", "promptLongAnswer")
			.val(contribution?contribution.argument:"")
			.appendTo($prompt)

		$promptSave = $("<input>")
			.attr("id", "promptSave")
			.attr("type", "button")
			.attr("value","Save")
			.addClass("button")
			.appendTo($prompt)
			.click(function() {
				saveContribution(start,end);
				$prompt.remove();
				$background.remove();
			})

		$promptCancel = $("<input>")
			.attr("id", "promptCancel")
			.attr("value","Cancel")
			.addClass("button")
			.appendTo($prompt)
			.click(function() {
				cancelContribution(start,end);
				$prompt.remove();
				$background.remove();
			})

		if(contribution) {
			$promptDelete = $("<input>")
				.attr("id", "promptDelete")
				.attr("value","Delete")
				.addClass("button")
				.appendTo($prompt)
				.click(function() {
					deleteContribution(contribution_id);
					$prompt.remove();
					$background.remove();
				})

		}

		$background.fadeIn();
		$prompt.fadeIn();

		ga('send', 'event', 'annotation', 'layerAction', 'create');
	}

	function deleteContribution(id) {
		contributions[id] = false;
		$(".contribution-" + id)
			.removeClass("selected")
			.data("contribution_id", "")
			.removeClass("contribution-" + id);
		ga('send', 'event', 'annotation', 'layerAction', 'delete');
	}

	function cancelContribution(start, end) {
		for(var x = Math.min(start,end); x <= Math.max(start,end); x++) {
			var contribution_id = getContributionId(x);
			var $word = $("#word-" + x);

			// Deselect if no contribution is associated
			if(contribution_id === "")
				$word.removeClass("selected")
		}
		ga('send', 'event', 'annotation', 'layerAction', 'cancel');
	}

	function saveContribution(start, end) {
		var contribution_id= getContributionId(start);
		var context_start = Math.max(0, start - 10);
		var context_end = Math.min(processed_words.length - 1, end + 10);
		var sentence = getSentence(start,end);
		var context = getSentence(context_start, context_end);

		var question = $("#promptQuestionSelect").val();
		if(question == "other") question = $("#promptQuestionText").val();
		var long_explanation = $("#promptLongAnswer").val();
		var short_explanation = $("#promptShortAnswer").val();

		var contribution = {
			"content": sentence,
			"context": context,
			"question": question,
			"argument": long_explanation,
			"summary": short_explanation
		};

		// Create a new contribution, or overwrite the old one?
		if(contribution_id === "") {
			contribution_id = next_contribution_id;
			contributions[contribution_id] = contribution;

			for(var x = Math.min(start,end); x <= Math.max(start,end); x++) {
				$("#word-" + x)
					.data("contribution_id", contribution_id)
					.addClass("contribution-" + contribution_id);
			}

			next_contribution_id++;
		} else {
			contributions[contribution_id] = contribution;
		}
		ga('send', 'event', 'annotation', 'layerAction', 'save');
	}

	function getContributionId(word) {
		return $("#word-" + word).data("contribution_id");
	}

	function getSentence(start,end) {
		return processed_words.slice(start, end + 1).join(" ");
	}

	// Save the entire layer
	function saveLayer() {
		var data = {
			"layer": {
				"contributions": contributions
			},
			"url": source_url,
			"text": source_text
		};

		$.ajax({
			"type": "post",
			"data": data,
			"url": "../api/layers",
			"dataType": "json"
		}).done(function(data) {
			layer_id = data.layer.id;
			url = data.url;
			var script = '<script src="' + BASE + '/js/goggles.js"></script><script>truthGoggles({server: "' + BASE + '",layerId: ' + layer_id + '});</script>';
			$("#layerCode").text(script);
			$("#layerUrl").html("<a href='//" + window.location.host + url + "'>http://" + window.location.host + url + "</a>");
			console.log(data);
		});
	}

	function buildContent(text) {
		var paragraphs = text.split( /[\n\r]/g );
		var content = [];
		for(var x in paragraphs) {
			var paragraph = paragraphs[x];
			if(paragraph == "")
				continue;
			var sentences = paragraph.match( /[^\.!\?]+([\.!\?]+( |$))?/g );
			content.push(sentences);
		}
		return content;
	}

	$("form").submit(function() {return false;});

	// Set up frame 1
	$("#contentText-form").submit(function() {
		var text = $("#contentText").val();
		source_text = text;
		var content = buildContent(text);
		loadContent(content)
		switchFrame(2);
		return false;
	});

	$("#contentUrl-form").submit(function() {
		var url = $("#contentUrl").val();
		source_url = url;
		$.ajax({
			"method": "get",
			"url": "../api/external",
			"data": {
				"url": url
			},
			dataType: "json"
		})
		.done(function(data){
			var div = $("<div>").html(data.html);
			var content = buildContent(div.text());
			loadContent(content)
			switchFrame(2);
		})
		.fail(function() {
			alert("Sorry, we couldn't extract the content from that page.  Please copy and paste the text manually.")
		})
		return false;
	});

	// Set up frame 2
	$("#contentSelection-form").submit(function() {
		saveLayer()
		switchFrame(3);
		return false;
	})

	// Start the experience
	switchFrame(1);

});