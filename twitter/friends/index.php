<?php
	require "../../ini.php";
	require_once('../../lib/twitterOAuth.php');
	require_once('../../lib/tweet.php');
	Tweet::$db = new PDO($conf['db']);
	//Tweet::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$to = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $_GET['k'], $_GET['s']);
if ($_GET['n']) {
	Tweet::$table = "timeline_". $_GET['n'];
	$here = $_SERVER['PHP_SELF'] ."?". $_SERVER['QUERY_STRING'];
}
else {
	$json = json_decode($to->OAuthRequest('http://twitter.com/account/verify_credentials.json', NULL, 'GET'));
	$here = $_SERVER['PHP_SELF'] ."?n=". $json->screen_name ."&". $_SERVER['QUERY_STRING'];
	Tweet::$table = "timeline_". $json->screen_name ;
	Tweet::create_table();
	header("Location: $here");
	die();
}

$json = json_decode($to->OAuthRequest('https://twitter.com/statuses/friends.json', NULL, 'GET'));

$tweets = array();
foreach ($json as $t) {
	$tweets[strtotime($t->status->created_at)] = new Tweet($t);
}
ksort($tweets);
foreach($tweets as $tweet) {
	if ($tweet->save()) break;
}


header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$name = $tweet->friend;
$status = $tweet->status;
$date = $tweet->time;
print "<html><head><title>tweeps</title></head><body><b>$name</b><br> $status<br><br><i>$date</i><br><a href='$here'>Next</a></body></html>";

