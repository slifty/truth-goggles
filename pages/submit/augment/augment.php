<html>
	<head>
    <link href='http://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="augment/augment.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?PHP echo($BASE_DIRECTORY); ?>js/lib/randomcolor.js"></script>
    <script>var BASE_PATH = "//<?php echo($_SERVER['HTTP_HOST'].$GOGGLES_DIRECTORY); ?>";</script>
		<script type="text/javascript" src="augment/augment.js"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-29964553-1', 'auto');
      ga('send', 'pageview');
    </script>
	</head>
	<body>
    <div id="header">
      <div id="logo"></div>
    </div>
		<div id="frames">
      <h1>Create a Credibility Layer...</h1>
			<div id="frame-1" class="frame">
        <div id="frame-1-web">
          <h2>...for a web site...</h2>
          <form id="contentUrl-form">
            <label for="contentUrl">Enter a URL:</label><input type="text" id="contentUrl"/>
            <input type="submit" class="button" value="Load this"/>
          </form>
        </div>
        <div id="frame-1-text">
          <h2>...or for a story</h2>
  				<form id="contentText-form">
  					<label for="contentText">Paste an article:</label>
            <textarea id="contentText"></textarea>
  					<input type="submit" class="button" value="Use this"/>
  				</form>
        </div>
			</div>
      <div id="frame-2" class="frame">
        <h2>...by asking your readers questions</h2>
        <form id="contentSelection-form">
          <label>Highlight places where you want people to think more carefully.</label>
          <div id="content"></div></li>
          <input type="submit" class="button" value="Publish"/>
        </form>
      </div>
      <div id="frame-3" class="frame">
        <h2>...and share it...</h2>
        <p>Give this link to your friends to help them think more carefully.  When they click your link, they will see the original story with your layer on top of it.</p>
        <div id="layerUrl"></div>
        <hr />
        <h2>...or embed it in your article</h2>
        <p>If you wrote this article you can embed your layer directly, so everyone is able to see it. Just copy the code below and paste it just like it was an embeddable video.</p>
        <div id="layerCode"></div>
      </div>
		</div>
	</body>
</html>