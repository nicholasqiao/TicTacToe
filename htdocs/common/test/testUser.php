<?php

require_once '../../../config.php';
require_once ROOT . '/php/db/User.php';

echo '<!DOCTYPE HTML>

<html>
	<head>
		<title>Test Harness for User Database Operations</title>
	</head>
<body>';

// User create/auth functions

$uid = User::newUser("mikeyman","mypassword");
echo $uid;

echo '<br>';

$uid_verify = User::auth("mikeyman","mypassword");
echo $uid_verify;

echo '<br>';

$uid_verify_bad = User::auth("mikeyman","notmypassword");
echo $uid_verify_bad;
