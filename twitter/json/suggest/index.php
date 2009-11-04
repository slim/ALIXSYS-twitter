<?php
	require "../../../lib/tweep.php";

$user = Tweep::byName($_GET['name']);
$user->load_friends();
$count = 0;
foreach ($user->friends as $friend) {
	$friend->load_friends();
	$count++;
	if ($count % 50 == 0) {
		$suggestions = $user->suggest_friends(40, 3);
		if (count($suggestions) >= 40) die(json_encode($suggestions));
	}
		
}
$suggestions = $user->suggest_friends(40);
print json_encode($suggestions);
