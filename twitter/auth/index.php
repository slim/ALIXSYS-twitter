<?php
	require "../../ini.php";
	require "../../lib/twitterOAuth.php";
	$db = new PDO($conf['db']);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['c'];
$req = $db->prepare("select * from users where id=:id");
$req->bindValue(':id',$id);
$req->execute();
$user = $req->fetch();
$to = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $user['key'], $user['secret']);

$url = $conf['status_url'] ."?k=". $user['key'] ."&s=". $user['secret'];


header("Location: $url");
