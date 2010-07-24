<?php
	require "../../ini.php";
	require_once('../../lib/tweet.php');
	require_once('../../lib/twitterOAuth.php');
	Tweet::$db = new PDO($conf['db']);
	Tweet::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	Tweet::oauth($conf['consumer_key'], $conf['consumer_secret']);

	Tweet::$twitter->OAuthRequest("https://$url", $update, 'POST');
