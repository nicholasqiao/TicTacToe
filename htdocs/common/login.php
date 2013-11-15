<?php

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

$username = $_REQUEST['login'];
$password = $_REQUEST['password'];

$uid = User::auth($username,$password);

#Need to remove the following chunk of code when auth is working
print($username);
print($password);
print($uid);
session_start();
$_SESSION['uid'] = 5;
header ('Location: ../main.html');

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