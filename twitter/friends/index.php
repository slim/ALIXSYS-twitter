<?php
	require "../../ini.php";
	require_once('../../lib/twitterOAuth.php');
	require_once('../../lib/tweet.php');
	Tweet::$db = new PDO($conf['db']);
	//Tweet::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	Tweet::$twitter = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $_GET['k'], $_GET['s']);

if ($_GET['n']) {
	Tweet::$table = "timeline_". $_GET['n'];
	$here = $_SERVER['PHP_SELF'] ."?". $_SERVER['QUERY_STRING'];
}
else {
	$user_name = Tweet::user_identity()->screen_name;
	$here = $_SERVER['PHP_SELF'] ."?n=$user_name&". $_SERVER['QUERY_STRING'];
	Tweet::$table = "timeline_$user_name";
	Tweet::create_table();
	header("Location: $here");
	die();
}

$tweets = Tweet::load_friends();
foreach($tweets as $tweet) {
	if ($tweet->save()) break;
}


header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$name = $tweet->friend;
$status = $tweet->status;
$date = date('Y.m.d H:m', strtotime($tweet->time));

print "<html><head><title>tweeps</title></head><body><b>$name</b><br> $status<br><br>$date<br><a href='$here'>Next</a></body></html>";

