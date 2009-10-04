<?php
	require "../../ini.php";
	require_once('../../lib/twitterOAuth.php');
	require_once('../../lib/tweet.php');
	Tweet::$db = new PDO($conf['db']);
	Tweet::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	Tweet::$table = "timeline_". $_GET['n'];

if ($_GET['o']) {
	$pos = $_GET['o'];
	$tweet = Tweet::byPosition($pos);
}
else {
	$tweet = Tweet::last_read();
	$pos = $tweet->position;
}
$prev = $pos - 1;
$next = $pos + 1;

$prev_url =  $_SERVER['PHP_SELF'] ."?o=". $prev ."&n=". $_GET['n'];
$next_url =  $_SERVER['PHP_SELF'] ."?o=". $next ."&n=". $_GET['n'];
$status_url = $conf['status_url'] ."?k=". $_GET['k'] ."&s=". $_GET['s'];

$name = $tweet->friend;
$status = $tweet->status;
$date = date('Y.m.d H:m', strtotime($tweet->time));

$status_enc = urlencode($status);

print "<html><head><title>tweeps</title></head><body><b>$name</b><br> $status<br><br>$date<br><a href='$prev_url'>Prev</a> | <a href='$next_url'>Next</a><br><a href='$status_url&status=@$name '>Reply</a><br><a href='$status_url&status=RT @$name $status_enc'>Retweet</a></body></html>";