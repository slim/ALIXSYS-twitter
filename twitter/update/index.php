<?php
	require_once('../../lib/twitterOAuth.php');
	$consumer_key = 'JCJZwzwdpgwLKbg3DBtmA';
	$consumer_secret = 'PmLAZEuyRFOCuh5qLb7aYvQOTpgeFcwQdTfjOdN64c';

$status = stripslashes($_GET['status']);
$to = new TwitterOAuth($consumer_key, $consumer_secret, $_GET['k'], $_GET['s']);
$to->OAuthRequest('https://twitter.com/statuses/update.xml', array('status' => $status), 'POST');
?>
<html>
<head>
<title><?php print $status ?></title>
</head>
<body>
Your status is now : <br><b><?php print $status ?></b>
</body>
</html>
