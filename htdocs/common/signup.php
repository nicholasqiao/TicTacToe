<?php

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

try {
	$user = User::newUser($username,$password);
}
catch (NotFoundException $e) {
	echo '<!DOCTYPE HTML><html><head><title>Username Taken</title></head><body>That username is taken. <a href="/signup.html">Go Back</a></body></html>';
	exit(0);
}
	

echo '<!DOCTYPE HTML><html><head><title>Success!</title></head><body>Account successfully created!. <a href="/home.html">Go Back</a></body></html>';


?>
