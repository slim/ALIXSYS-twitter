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
	if (!$user_name) die("Sorry, I can't recognize you. Twitter is maybe down, retry later.");
	$here = $_SERVER['PHP_SELF'] ."?n=$user_name&p=1&". $_SERVER['QUERY_STRING'];
	Tweet::$table = "timeline_$user_name";
	Tweet::create_table();
	header("Location: $here", TRUE, 301);
	die();
}

Tweet::mark_all_as_read();
Tweet::load_timeline();
$tweet = Tweet::first_unread();
if (!$tweet instanceof Tweet) {
	$page++;
	$nbr_friends = count(Tweet::load_timeline($page));
	if ($nbr_friends < 97) {
		$page = 1;
	}
	$tweet = Tweet::first_unread();
}

if (!$tweet instanceof Tweet) {
	$last = Tweet::last()->position;
	$last_url =  $conf['tweet_url'] ."?k=". $_GET['k'] ."&s=". $_GET['s']."&o=". $last ."&n=". $_GET['n'];
	$fresh_url =  $_SERVER['PHP_SELF'] ."?n=". $_GET['n'] ."&p=". $page ."&k=". $_GET['k'] ."&s=". $_GET['s'];
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	die("<html><head><title>tweeps</title></head><body><b>Nothing happened :)</b><br><a href='$last_url'>Last tweet</a> | <a href='$fresh_url'>Fresh tweets</a></body></html>");
}
else {
	$tweet_url =  $conf['tweet_url'] ."?n=". $_GET['n'] ."&k=". $_GET['k'] ."&s=". $_GET['s'];

	header("Location: $tweet_url", TRUE, 307);
}
