$(function() {
	var processed_sentences = [];
	var used_sentences = [];
	var current_sentence = 0;
	var credibility_content = [];

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
				console.log(used_sentences);
				$this.addClass("selected");
				$this.css("background", randomColor({luminosity: 'light', hue: 'blue'}));
				console.log("ADDED");
			}
			else {
				used_sentences.splice(used_sentences.indexOf(sentence_id),1);
				$this.removeClass("selected");
				$this.css("background", "");

				console.log("REMOVED");
			}
		})
	}


	// Load in the list of available questions
	function loadQuestions(sentence_number) {
		var sentence = processed_sentences[used_sentences[sentence_number]];
		
		var $sentence = $("#credibility-sentence")
			.text(sentence);

		var $credibility_prompts = $("#credibility-prompts");
		for(var x in question_list) {
			var question_type = question_list[x];
			var questions = question_type["questions"];
			var title = question_type["title"];
			var $question_type = $("<div>")
				.addClass("question_type")
				.appendTo($credibility_prompts);

			var $question_type_title = $("<h4>")
				.text(title)
				.click(function() {
					$(this).parent().find("ul")
						.toggle();
				})
				.appendTo($question_type)

			var $question_type = $("<ul>")
				.hide()
				.appendTo($question_type);

			for(var y in questions) {
				var question = questions[y];
				console.log(question);
				var $question = $("<li>")
					.appendTo($question_type)
				var $question_input = $("<input>")
					.addClass("checkbox")
					.attr("type","checkbox")
					.attr("id","question-" + x + "-" + y)
					.appendTo($question);
				var $question_label = $("<label>")
					.addClass("checkbox")
					.attr("for","question-" + x + "-" + y)
					.text(question)
					.appendTo($question);
			}
		}
	}


	// Set up frame 1
	$("#frame-1 #content-load-form").submit(function() {
		var content = $("#article-input").text();
		var paragraphs = str.match( /\n|\r/g );
		for(var x in paragraphs) {
			var paragraph = paragraphs[x];
			console.log(paragraph);
		}
		return false;
		var result = str.match( /[^\.!\?]+[\.!\?]+/g );
		loadContent(
			[["This is a test.",
			  "It has a few sentences!"],
			 ["But also a few paragraphs."]]);
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


	// Start the experience
	loadContent(
		[["This is a test.",
		  "It has a few sentences!"],
		 ["But also a few paragraphs."]]);
	switchFrame(2);
	switchFrame(1);

});