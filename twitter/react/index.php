<?php
	require "../../ini.php";
	require_once('../../lib/twitterOAuth.php');
	require "../../lib/tweet.php";
//http://twitter.alixsys.com/status/?k=133230547-Xi6Ko6YmPw0DBnfzZq8GhK14gG93PlcOiblpWj2A&s=zr5xp507sBJmJ9T6sEWo9DBnLMHGIib6t3H1kZc9E

	Tweet::$twitter = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $_GET['k'], $_GET['s']);

$tweets = Tweet::fromSearch(json_decode(file_get_contents("http://search.twitter.com/search.json?result_type=recent&q=".urlencode($_GET['keyword']))));

print json_encode($tweets);

foreach ($tweets as $t) {
	$t->reply($_GET['reply']);
}
