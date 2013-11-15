<?php

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

$username = $_REQUEST['login'];
$password = $_REQUEST['password'];

$uid = User::auth($username,$password);

print($username);
print($password);
print($uid);

if ($uid < 0 || $uid == NULL)
{
	echo '<!DOCTYPE HTML><html><head><title>Username Taken</title></head><body>Invalid login information! <a href="../home.html">Go Back</a></body></html>';
}
else
{
    session_start();
    $_SESSION['uid'] = $uid;
	header ('Location: ../main.html');
}
?>