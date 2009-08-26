<?php
	require "../../ini.php";
	require_once('../../lib/twitterOAuth.php');
	require_once('../../lib/tweet.php');
	Tweet::$db = new PDO($conf['db']);
	//Tweet::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	Tweet::$twitter = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $_GET['k'], $_GET['s']);

if ($_GET['n'] && $_GET['p']) {
	Tweet::$table = "timeline_". $_GET['n'];
	$here = $_SERVER['PHP_SELF'] ."?". $_SERVER['QUERY_STRING'];
	$page = $_GET['p'];
}
else {
	$user_name = Tweet::user_identity()->screen_name;
	$here = $_SERVER['PHP_SELF'] ."?n=$user_name&p=1&". $_SERVER['QUERY_STRING'];
	Tweet::$table = "timeline_$user_name";
	Tweet::create_table();
	header("Location: $here");
	die();
}

$tweet = Tweet::first_unread();
if ($_GET['fresh'] || !$tweet instanceof Tweet) {
	Tweet::mark_all_as_read();
	$nbr_friends = count(Tweet::load_friends($page));
	if ($nbr_friends < 98) {
		$page = 1;
	}
	else {
		$page++;
	}
	$tweet = Tweet::first_unread();
}

$next_url =  $_SERVER['PHP_SELF'] ."?n=". $_GET['n'] ."&p=". $page ."&k=". $_GET['k'] ."&s=". $_GET['s'];
$status_url = $conf['status_url'] ."?k=". $_GET['k'] ."&s=". $_GET['s'];

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

if (!$tweet instanceof Tweet) {
	die("<html><head><title>tweeps</title></head><body><b>Nothing happened :)</b><br><a href='$next_url&fresh=1'>Fresh tweets</a></body></html>");
}

$name = $tweet->friend;
$status = $tweet->status;
$date = date('Y.m.d H:m', strtotime($tweet->time));

$tweet->mark_as_read();
$status_enc = urlencode($status);

print "<html><head><title>tweeps</title></head><body><b>$name</b><br> $status<br><br>$date<br><a href='$next_url'>Next</a><br><a href='$status_url&status=RT @$name $status_enc'>Retweet</a><br><a href='$next_url&fresh=1'>Fresh tweets</a></body></html>";

