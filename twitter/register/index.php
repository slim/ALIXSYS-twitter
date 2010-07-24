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
if (strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'fr') !== FALSE) {
	require "index.fr.php";
	die();
} 
?>
<html>
<head>
<title>ALIXSYS twitter Authenticated</title>
<style>
@import url(../alixsys.css);
</style>
</head>
<body>
<center style="margin-bottom: 100px">
<a href="http://alixsys.com"><img src="../png/AliXsys-identite.png" border="0" /></a>
<p id="description">Congratulations! You are authenticated</p>
</center>
<p><a class="bigbutton" href="../doc/configure-mobile.php?axk=<?php print $code ?>">Setup your mobile phone</a></p>
or
<p><a class="bigbutton" href="../doc/api.php?axk=<?php print $code ?>">Use the API</a></p>
</body>
</html>
