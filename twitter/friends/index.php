<?php
	require "../../ini.php";
	require_once('../../lib/twitterOAuth.php');
	require_once('../../lib/tweet.php');
	Tweet::$db = new PDO($conf['db']);
	//Tweet::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	Tweet::$table = "timeline_". $_GET['n'];

$to = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $_GET['k'], $_GET['s']);
$json = json_decode($to->OAuthRequest('https://twitter.com/statuses/friends.json', NULL, 'GET'));

$tweets = array();
foreach ($json as $t) {
	$tweets[strtotime($t->status->created_at)] = new Tweet($t);
}
ksort($tweets);
foreach($tweets as $tweet) {
	if ($tweet->save()) break;
}
$name = $tweet->friend;
$status = $tweet->status;
$date = $tweet->time;
print "<b>$name</b><br> $status<br><br><i>$date</i>";
