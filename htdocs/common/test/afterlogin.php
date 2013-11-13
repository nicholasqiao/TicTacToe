<?php session_start();

echo'<!DOCTYPE HTML>
<html>
<head>
<title>After Log In</title>
</head>
<body>';


if (isset($_SESSION['uid']))
	echo '<p>You are logged in with uid ' . $_SESSION['uid'] . '.</p>';
else
	echo '<p>You are not logged in.</p>';

echo '</body>
</html>';
