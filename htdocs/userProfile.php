<?php
/* User Profile
 * Should pass a user ID using GET, variable 'user'. Do not have to be signed in.
 * e.g. userProfile.php?user=12
 *
 * If that doesn't exist, will default to signed in user's profile
 * If not signed in and no variable passed with GET then will display not found error.
 * 
 * This page isn't designed to be pretty yet, just to make sure it generally works and
 * gets the required information.
 */

require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(ROOT . '/php/db/User.php');

session_start();


if (!isset($_REQUEST['user'])) {
	if (!isset($_SESSION['uid'])) {
		header ('Location: /home.html');
		exit(0);
	}
	else 
		$userid = $_SESSION['uid'];
	}

else 
	$userid = $_REQUEST['user'];

/*  Figure out if this is the logged in user's page to display a challenge button
 * and maybe some other stuff
 */


if (isset($_SESSION['uid']) && $userid == $_SESSION['uid'])
	$user_is_me = true;
else
	$user_is_me = false;

try {
	$user = new User($userid);
}
// If the user doesn't exist, display an error.
// Could also handle this by just redirecting to home.
catch (NotFoundException $e) {
	echo '<!DOCTYPE HTML><html>
	      <head><title>Error: User Not Found</title></head>
	      <body>
	      <p><b>Error:</b> User not found. <a href="/home.html">Home</a></p>
	      </body></html>';
	exit(0);
}

$email = $user->getEmail();
$stats = $user->getStats(); // stats is an array
$gameRequests = $user->getRequests();
$formattedReq = JSON_encode($gameRequests);
//$achievements = $user->getAchievements(); // achievements functionality to be implemented


echo '
<!DOCTYPE HTML>
<html>
<head>
<title>Profile for ' . $email . '</title>
</head>
<body>
<h1>User ' . $email . '</h1>';

if (!$user_is_me)
	echo '<p><a href="#">Challenge!</a></p>';

echo '
<table>
<tr> <td colspan="2">User Statistics</td> </tr>
<tr>
	<td>Wins:</td><td>' . $stats["win"] . '</td>
</tr>
<tr>
	<td>Losses:</td><td>' . $stats["loss"] . '</td>
</tr>
<tr>	<td>Ties:</td><td>' . $stats["tie"] . '</td>
</tr>
</table>
';

echo '
<table>
<tr> <td colspan="2">Game Requests</td> </tr>
<tr>
	<td>Requests:</td><td>' . $formattedReq . '</td>
</tr>
</table>
';

