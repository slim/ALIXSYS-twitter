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
	$title = $tweet->status;
}
else {
	$tweet = Tweet::first_unread() or Tweet::last();
	$pos = $tweet->position;
	$title = "tweeps";
}
$prev = $pos - 1;
$next = $pos + 1;
$last = Tweet::last()->position;

$status_url = $conf['status_url'] ."?k=". $_GET['k'] ."&s=". $_GET['s'];
$friends_url = $conf['friends_url'] ."?k=". $_GET['k'] ."&s=". $_GET['s'] ."&n=". $_GET['n'] ."&fresh=1";
$prev_url =  $_SERVER['PHP_SELF'] ."?k=". $_GET['k'] ."&s=". $_GET['s']."&o=". $prev ."&n=". $_GET['n'];
if ($next > $last) {
	$next_url = $friends_url;
}
else {
	$next_url =  $_SERVER['PHP_SELF'] ."?k=". $_GET['k'] ."&s=". $_GET['s'] ."&o=". $next ."&n=". $_GET['n'];
}

$name = $tweet->friend;
$status = $tweet->status;
$date = date('Y.m.d H:m', strtotime($tweet->time));

$status_enc = urlencode($status);

print "<html><head><title>$title</title></head><body><b>$name</b><br> $status<br><br>$date<br><a href='$prev_url'>Prev</a> | <a href='$next_url'>Next</a><br><a href='$status_url&status=@$name '>Reply</a><br><a href='$status_url&status=RT+@$name+$status_enc'>Retweet</a><br><a href='$friends_url'>Fresh tweets</a></body></html>";
$tweet->mark_as_read();
