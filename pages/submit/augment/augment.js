$(function() {
	var processed_sentences = [];
	var used_sentences = [];
	var current_sentence = 0;
	var credibility_content = [];

	var layer_id = 0;

	var question_list = [
		{
			title: "Who",
			questions: [
			  "Who is saying this?",
			  "Who else is involved?",
			  "Who benefits from this?"
			]
		},
		{
			title: "What",
			questions: [
			  "What other evidence should I consider?",
			  "What does this change?",
			  "What does this mean for me?"
			]
		},
		{
			title: "When",
			questions: [
			  "When did this happen?",
			  "How often does this happen?",
			]
		},
		{
			title: "Where",
			questions: [
			  "Where does this have an impact?",
			  "Where did this happen?"
			]
		},
		{
			title: "Why",
			questions: [
			  "Why did this happen?",
			  "Why should I care?",
			]
		},
	];


	// Switch frames in the process
	function switchFrame(id) {
		var $frame = $("#frame-" + id);
		$(".frame").hide();
		$frame.show();
	}


	// Load in content from a JSON content list with paragraphs and sentences in individual strings.
	// Example:
	// [["Sentence 1", "Sentence 2"],["Sentence 3, Paragraph 2"]]
	function loadContent(data) {
		var $selectable_content = $("#selectable-content");
		$selectable_content.empty();

		var sentence_id = 0;

		for(var x in data) {
			var paragraph = data[x];
			var $paragraph = $("<p>")
				.appendTo($selectable_content);

			for(var y in paragraph) {
				var sentence = paragraph[y];
				processed_sentences[sentence_id] = sentence;
				var $sentence = $("<span>")
					.addClass("sentence")
					.data("sentence_id", sentence_id)
					.appendTo($paragraph);

				var words = sentence.split(" ");
				for(var z in words) {
					var word = words[z];
					var $word = $("<span>")
						.addClass("word")
						.text(word + " ")
						.appendTo($sentence);
				}
				sentence_id++;
			}
		}

		$(".sentence").click(function() {
			var $this = $(this);
			var sentence_id = $this.data("sentence_id");
			if($.inArray(sentence_id, used_sentences) == -1) {
				used_sentences.push(sentence_id);
				$this.addClass("selected");
				$this.css("background", randomColor({luminosity: 'light', hue: 'blue'}));
			}
			else {
				used_sentences.splice(used_sentences.indexOf(sentence_id),1);
				$this.removeClass("selected");
				$this.css("background", "");
			}
		})
	}


	// Load in the list of available questions
	function loadQuestions(sentence_number) {
		var sentence = processed_sentences[used_sentences[sentence_number]];
		
		var $sentence = $("#credibility-sentence")
			.text(sentence);

		var $credibility_prompts = $("#credibility-prompts");
		$credibility_prompts.empty();
		var $select_prompt = $("<select>")
			.appendTo($credibility_prompts);
		for(var x in question_list) {
			var question_type = question_list[x];
			var questions = question_type["questions"];
			var title = question_type["title"];

			for(var y in questions) {
				var question = questions[y];
				var $question = $("<option>")
					.attr("value", question)
					.text(question)
					.appendTo($select_prompt)
			}
		}
	}

	// Save this single claim
	function saveClaim(sentence_number) {
		var sentence = processed_sentences[used_sentences[sentence_number]];
		var long_explanation = $("#credibility-long-information").val();
		var short_explanation = $("#credibility-short-information").val();
		$("#credibility-short-information").val("");
		$("#credibility-long-information").val("");

		var content = {
			"claim": sentence,
			"long": long_explanation,
			"short": short_explanation
		};

		credibility_content.push(content);
	}

	// Save the entire layer
	function saveLayer() {
		$.ajax({
			"type": "post",
			"data": credibility_content
		}).done(function(data) {
			layer_id = data.layer_id;
		})
	}


	// Set up frame 1
	$("#frame-1 #content-load-form").submit(function() {
		var content = $("#article-input").val();
		var paragraphs = content.split( /[\n\r]/g );
		var prepared_content = [];
		for(var x in paragraphs) {
			var paragraph = paragraphs[x];
			if(paragraph == "")
				continue;
			var sentences = paragraph.match( /[^\.!\?]+([\.!\?]+ )?/g );
			prepared_content.push(sentences);
		}
		loadContent(prepared_content)
		switchFrame(2);
		return false;
	});

	// Set up frame 2
	$("#frame-2 #content-selection-form").submit(function() {
		loadQuestions(0);
		switchFrame(3);
		return false;
	})

	// Set up frame 3
	$("#frame-3 #credibility-definition-form").submit(function() {
		saveClaim(current_sentence);
		current_sentence++;
		if(current_sentence == used_sentences.length) {
			saveLayer();
			switchFrame(4);
		} else {
			loadQuestions(current_sentence);
		}
		return false;
	})

	// Start the experience
	loadContent(
		[["This is a test.",
		  "It has a few sentences!"],
		 ["But also a few paragraphs."]]);
	switchFrame(2);

});