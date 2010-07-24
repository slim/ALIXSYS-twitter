<?php
	require "../../ini.php";
	require_once('../../lib/tweet.php');
	require_once('../../lib/twitterOAuth.php');
	Tweet::$db = new PDO($conf['db']);
	Tweet::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	Tweet::oauth($conf['consumer_key'], $conf['consumer_secret']);
	$address = $_GET['axa'];


	if ($_POST) {
		print Tweet::$twitter->OAuthRequest("https://$address", $_POST, 'POST');
	}
	else {
		unset($_GET['axk']);
		unset($_GET['axa']);
		print Tweet::$twitter->OAuthRequest("https://$address", $_GET, 'GET');
	}
