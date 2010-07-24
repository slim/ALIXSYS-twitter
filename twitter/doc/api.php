<html>
<head>
<title>ALIXSYS twitter API doc</title>
<style>
@import url(../alixsys.css);
</style>
</head>
<body>
<center style="margin-bottom: 100px">
<a href="http://alixsys.com"><img src="../png/AliXsys-identite.png" border="0" /></a>
<h1 id="description">ALIXSYS twitter API OAuth proxy Documentation</h1>
</center>
<h2>Update your status with method GET</h2>
	<code>http://twitter.alixsys.com/update/?axk=<b><?php print $_GET['axk'] ?></b>&status=[your status here]</code>
<h2>Access any twitter API</h2>
<p>You can use ALIXSYS twitter API proxy to access any <a href="http://dev.twitter.com/doc">Twitter original API</a> you just need to proxy it through your custom URL : </p>
<code>http://twitter.alixsys.com/api/<b><?php print $_GET['axk'] ?></b>/<i>[orginal twitter API address without "https://" goes here]</i></code>. 

<p>For example to get your timeline in JSON : </p>
<code>http://twitter.alixsys.com/api/<b><?php print $_GET['axk'] ?></b>/api.twitter.com/1/statuses/home_timeline.json</code>
<p id="signature">by <a href="http://alixsys.com">ALIXSYS</a></p>
</body>
</html>

