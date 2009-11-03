<?php
	error_reporting(0);
	require "../../../lib/tweep.php";

$user = Tweep::byName($_GET['name']);
$user->load_friends();
foreach ($user->friends as $friend) {
	$friend->load_friends();
}
$suggestions = $user->suggest_friends(40);
print json_encode($suggestions);
