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

$user = User::newUser("mikeg","mypassword");
var_dump($user);

echo '<br>';

$user2 = User::auth("mikeg","mypassword");
var_dump($user2);
echo $user2->uid();

echo '<br>';

$user3 = User::auth("mikeg","notmypassword");
var_dump($user3);

var_dump($user2->getStats());
