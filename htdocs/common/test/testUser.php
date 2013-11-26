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

try {
$user = User::newUser("mikeg","mypassword");
}
catch (NotFoundException $e) { }

var_dump($user);

echo '<br>';

$user2 = User::auth("mikeg","mypassword");
var_dump($user2);
echo $user2->uid();

echo '<br>';

$result = $user2->gameResult('win');
$result = $result && $user2->gameResult('win');
$result = $result && $user2->gameResult('loss');
$result = $result && $user2->gameResult('loss');
$result = $result && $user2->gameResult('tie');
echo $result;

$str = "win";
echo strcmp($str,"win");

if (strcmp($str,"win")) echo "hi";

try {
	$user3 = User::auth("mikeg","notmypassword");
}
catch (NotFoundException $e) { }

var_dump($user3);

var_dump($user2->getStats());
