<?php
	require "../../ini.php";
	require_once('../../lib/tweet.php');
	require_once('../../lib/twitterOAuth.php');
	Tweet::$twitter = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $_GET['k'], $_GET['s']);

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$status = stripslashes($_GET['status']);
Tweet::update($status);
?>
<html>
<head>
<title><?php print $status ?></title>
</head>
<body>
Your status is now : <br><b><?php print $status ?></b>
</body>
</html>
