<?php

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

$uid = User::newUser($username,$password);

if (uid < 0)
	echo '<!DOCTYPE HTML><html><head><title>Username Taken</title></head><body>That username is taken. <a href="/signup.html">Go Back</a></body></html>';
else {
	session_start();
	$_SESSION['uid'] = $uid;
	
	header('Location: /common/test/afterlogin');
}
