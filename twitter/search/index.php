<?php
	require "../../ini.php";
	require_once('../../lib/tweet.php');
	Tweet::$db = new PDO($conf['db']);
	Tweet::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	Tweet::$table = "timeline_". $_GET['n'];
?>
<html>
<head>
<title>search</title>
</head>
<body>
<form action="." method="get">
<input name="n" type="hidden" value="<?php print $_GET['n'] ?>"/>
<input name="q" type="text" maxlength="140" value="<?php print $_GET['q'] ?>"/>
<input type="submit" value="search" />
</form>
<hr />
<?php 
if ($_GET['q']) {
	$perpage = $_GET['num'] ? $_GET['num'] : 10;
	$tweets = Tweet::search($_GET['q'], $perpage);
	foreach ($tweets as $t) {
		$friend = $t->friend;
		$time = $t->time;
		$status = preg_replace('/(http:\/\/[^ ]+)/','<a href="$1">$1</a>', $t->status);
		print "<p><b>$friend</b> $status <sub>$time</sub></p>";
	}
}
?>
</body>
</html>
