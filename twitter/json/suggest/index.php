<?php
	require "../../../lib/tweep.php";

$user = Tweep::byName($_GET['name']);
$user->load_friends();
foreach ($user->friends as $friend) {
	$friend->load_friends();
}
$suggestions = $user->suggest_friends();
print json_encode($suggestions);
