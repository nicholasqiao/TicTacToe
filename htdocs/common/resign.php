<?php
/* resign.php
 * takes 2 GET arguments: gid, UID of resigner
 */

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';
require_once ROOT . '/php/db/Game.php';


if (!isset($_REQUEST['gid'])) {
	echo 'error';
	exit(0);
}

if (!isset($_REQUEST['uid'])) {
	echo 'error';
	exit(0);
}

$resignUid = $_REQUEST['uid'];
$gid = $_REQUEST['gid'];

$info = Game::info($gid);

if (!is_null($info['winner']))
	exit(0);

if ($info['uid_one'] == $resignUid)
	$winner = $info['uid_two'];
else if ($info['uid_two'] == $resignUid)
	$winner = $info['uid_one'];
else {
	echo 'error';
	exit(0);
}

Game::finish($gid,$winner);

if ($info['ranked']) {
	User::recordWin($winner);
	User::recordLoss($resignUid);
}

echo 'success';
