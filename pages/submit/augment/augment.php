<html>
	<head>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="augment/augment.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?PHP echo($BASE_DIRECTORY); ?>js/lib/randomcolor.js"></script>
		<script type="text/javascript" src="augment/augment.js"></script>
	</head>
	<body>
		<div id="content">
			<h1>Truth Goggles: Create a Credibility Layer</h1>
			<div id="frame-1" class="frame">
				<h2>What do you want to create a layer for?</h2>
				<form id="content-load-form">
					<ul>
						<li><label for="article-input">Use an article:</label><textarea id="article-input"></textarea></li>
						<li><input type="submit" value="Use this content"/></li>
					</ul>
				</form>
			</div>
      <div id="frame-2" class="frame">
        <h2>When should readers be thinking more carefully?</h2>
        <p>Click on the phrases that you want people to think about.</p>
        <div id="selectable-content"></div>
        <form id="content-selection-form">
          <ul>
            <li><input type="submit" /></li>
          </ul>
        </form>
      </div>
      <div id="frame-3" class="frame">
        <h2>Now help people think.</h2>
        <div id="credibility-sentence"></div>
        <form id="credibility-definition-form">
          <ul>
            <li><h3>What should your readers ask themselves when they see this?</h3></li>
            <li>
              <div id="credibility-prompts"></div>
            </li>
            <li><h3>What should they consider when asking these questions?</h3></li>
            <li>
              <div id="credibility-information"></div>
            </li>
            <li><input type="submit" /></li>
          </ul>
        </form>
      </div>
      <div id="frame-4" class="frame">
        <h2>Insert your Credibility Layer</h2>
        <div id="credibility-code"></div>
      </div>
		</div>
	</body>
</html>