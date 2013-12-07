<?php
/* forgotPass.php
 * pass username through GET
 */


require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

$errmsg = "An error occured. Please <a href='/forgotpassword.html'>try again</a>.";

if (!isset($_REQUEST['username']))
	echo $errmsg;

$uid = User::usernameToUid($_REQUEST['username']);

if (is_null($uid)) {
	echo 'Username not found. <a href="/forgotpassword.html>Go Back</a>"';
	exit(0);
}

$token = User::forgotPass($uid);

//here you would send the email, but we're just going to echo the link.

echo 'An email has been sent containing a link that you can use to reset your password.';

echo '<br><br>(Below is the link you would see in the email you would recieve.)';

echo '<br><br><a href="/resetPassword.php?uid=' . $uid . '&token=' . $token . '">Reset Password</a>';



