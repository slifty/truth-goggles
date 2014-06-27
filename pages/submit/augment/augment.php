<html>
	<head>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="augment/augment.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?PHP echo($BASE_DIRECTORY); ?>js/lib/randomcolor.js"></script>
    <script>var BASE_PATH = "//<?php echo($_SERVER['HTTP_HOST'].$BASE_DIRECTORY); ?>";</script>
		<script type="text/javascript" src="augment/augment.js"></script>
	</head>
	<body>
		<div id="frames">
      <div id="logo"></div>
			<h1>Create a Credibility Layer</h1>
			<div id="frame-1" class="frame">
        <h2>Use a web site...</h2>
        <form id="contentUrl-form">
          <label for="contentUrl">Enter a URL:</label><input type="text" id="contentUrl"/>
          <input type="submit" class="button" value="Load this"/>
        </form>
        <hr />
        <h2>Or use an article's text...</h2>
				<form id="contentText-form">
					<label for="contentText">Paste an article:</label>
          <textarea id="contentText"></textarea>
					<input type="submit" class="button" value="Use this"/>
				</form>
        
			</div>
      <div id="frame-2" class="frame">
        <h2>Where should readers be thinking more carefully?</h2>
        <form id="contentSelection-form">
          <label>Highlight phrases you want people to think about.</label>
          <div id="content"></div></li>
          <input type="submit" class="button" value="Publish"/>
        </form>
      </div>
      <div id="frame-3" class="frame">
        <h2>Share your layer...</h2>
        <p>Give this link to your friends to help them think more carefully about this article.  When they click your link, they will see the original story with your layer on top of it.</p>
        <div id="layerUrl"></div>
        <hr />
        <h2>Or embed it in your article...</h2>
        <p>If you wrote this article you can embed your layer directly, so everyone is able to see it. Just copy the code below and paste it just like it was an embeddable video.</p>
        <div id="layerCode"></div>
      </div>
		</div>
	</body>
</html>