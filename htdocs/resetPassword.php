<?php
/* resetPassword.php
 * user should be directed here via a link in their email
 * GET arguments: uid, token
 */

require_once '../config.php';
require_once ROOT . '/php/db/User.php';

$errmsg = 'An error has occured. <a href="/index.html">Home</a>';

if (!isset($_REQUEST['uid']) || !isset($_REQUEST['token'])) {
	echo $errmsg;
	exit(0);
}

$uid = $_REQUEST['uid'];
$token = $_REQUEST['token'];

if (!User::verifyResetToken($uid, $token)) {
	echo 'Invalid reset token. <a href="/index.html">Home</a>';
	exit(0);
}

echo '<!DOCTYPE HTML><html><head><title>Reset Password - Wombat TTT</title></head><body><div>Please enter your new password: <form action="./common/updatePass.php" method="GET"><input type="password" name="password"> <input type="hidden" name="uid" value=' . $uid . '><input type="hidden" name="token" value='.$token.'><input type="submit" value="Submit"></form> </div>';


