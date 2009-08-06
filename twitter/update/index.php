<?php
	require_once('../../lib/twitterOAuth.php');
	$consumer_key = 'JCJZwzwdpgwLKbg3DBtmA';
	$consumer_secret = 'PmLAZEuyRFOCuh5qLb7aYvQOTpgeFcwQdTfjOdN64c';

	$to = new TwitterOAuth($consumer_key, $consumer_secret, $_GET['k'], $_GET['s']);
	$to->OAuthRequest('https://twitter.com/statuses/update.xml', array('status' => $_GET['status']), 'POST');
?>
<html>
<head>
<title><?php print $_GET['status'] ?></title>
</head>
<body>
Your status is now : <?php print $_GET['status'] ?>
</body>
</html>
