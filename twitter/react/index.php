<?php
	require "../../ini.php";
	require_once('../../lib/twitterOAuth.php');
	require "../../lib/tweet.php";
//http://twitter.alixsys.com/status/?k=133230547-Xi6Ko6YmPw0DBnfzZq8GhK14gG93PlcOiblpWj2A&s=zr5xp507sBJmJ9T6sEWo9DBnLMHGIib6t3H1kZc9E

	Tweet::$twitter = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $_GET['k'], $_GET['s']);

$search_url = "http://search.twitter.com/search.json?result_type=recent&q=".urlencode(html_entity_decode($_GET['keyword'],ENT_NOQUOTES,'UTF-8'));
$tweets = Tweet::fromSearch(json_decode(file_get_contents($search_url)));

print json_encode($tweets);

foreach ($tweets as $t) {
	$t->reply($_GET['reply']);
}
