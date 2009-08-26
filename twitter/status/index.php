<?php
	require "../../ini.php";
	$myfriends = $conf['friends_url'] ."?". $_SERVER['QUERY_STRING'];
	$status = "";
	if ($_GET['status']) {
		$status = $_GET['status'];
	}
?>
<html>
<head>
<title>tweet</title>
</head>
<body>
<form action="../update/">
<input name="k" type="hidden" value="<?php print $_GET['k'] ?>"/>
<input name="s" type="hidden" value="<?php print $_GET['s'] ?>"/>
<input name="status" type="text" maxlength="140" value="<?php print $status ?>"/>
<input type="submit" value="update" />
&nbsp;&nbsp;<a href="<?php print $myfriends ?>">Friends</a>
<p>tip: bookmark this page</p>
</form>
</body>
</html>
