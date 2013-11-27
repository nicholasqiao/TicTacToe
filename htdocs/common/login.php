<?php

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

$username = $_REQUEST['login'];
$password = $_REQUEST['password'];

try {
	$user = User::auth($username,$password);
}
catch (NotFoundException $e) {
	echo '<!DOCTYPE HTML><html><head><title>Username Taken</title></head><body>Invalid login information! <a href="../home.html">Go Back</a></body></html>';
	exit(0);
}


session_start();
$_SESSION['uid'] = $user->uid();
header ('Location: /main.html');

?>
