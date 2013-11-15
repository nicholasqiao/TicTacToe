<?php

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

$uid = User::newUser($username,$password);

if ($uid < 0)
	echo '<!DOCTYPE HTML><html><head><title>Username Taken</title></head><body>That username is taken. <a href="../signup.html">Go Back</a></body></html>';
else 
	echo '<!DOCTYPE HTML><html><head><title>Username Taken</title></head><body>Account successfully created!. <a href="../home.html">Go Back</a></body></html>';
?>