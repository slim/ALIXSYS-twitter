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
</head>
<body>
<center>
<img src="../gif/AliXsys-logo-32x32.gif" />
</center>
<ol>
	<li>Grab your mobile</li>
	<li>Go to <i>http://twitter.alixsys.com</i> and enter this code : <b><?php print $code ?></b></li>
	<li>Bookmark the status update page</li>
	<li>Update your status FTW!</li>
</ol>
</body>
</html>
