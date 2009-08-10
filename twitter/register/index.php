<?php
	require "../../ini.php";
	require "../../lib/twitterOAuth.php";
	$db = new PDO($conf['db']);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();
if (!$_GET['oauth_token']) {
	$to = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret']);
	$tok = $to->getRequestToken();
	$_SESSION['oauth_token'] = $tok['oauth_token'];
	$_SESSION['oauth_token_secret'] = $tok['oauth_token_secret'];
	$request_link = $to->getAuthorizeURL($tok['oauth_token']);
	header("Location: $request_link"); die();
}
$to = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']); 
$tok = $to->getAccessToken();
$code = uniqid();
$db->query("insert into users (id, key, secret) values ('$code', '". $tok['oauth_token'] ."', '". $tok['oauth_token_secret'] ."')");
?>
<html>
<head>
<title>ALIXSYS twitter registration</title>
<style>
body {
	background-color: #4a7428; 
}
li {
	color: white; 
	font-size: 48pt;
	font-family: "fixed";
	margin-left: 10px;
}
li span {
	font-size: 12pt;
}
</style>
</head>
<body>
<center>
<img src="../png/AliXsys-identite.png" />
</center>
<ol>
	<li><span>Grab your mobile</span></li>
	<li><span>Go to <i>http://twitter.alixsys.com</i> and enter this code : <b><?php print $code ?></b></span></li>
	<li><span>Bookmark the status update page</span></li>
	<li><span>Update your status FTW!</span></li>
</ol>
</body>
</html>