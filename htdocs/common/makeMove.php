<?php
/* makeMove.php
 * pass uid, row, and col as GET params
 * makeMove.php?gid=5&uid=4&row=2&col=1
 *
 *  Will echo 'error' for invalid move or other error and
 * 'success' for successful move made.
 */

require_once '../../config.php';
require_once ROOT . '/php/app/GameBoard.php';

if(!isset($_REQUEST['gid']) || !isset($_REQUEST['uid']) || !isset($_REQUEST['row']) || !isset($_REQUEST['col'])) {
	echo 'error';
	exit(0);
}

if (GameBoard::makeMove($_REQUEST['gid'],$_REQUEST['uid'],$_REQUEST['row'],$_REQUEST['col'])) {
	echo 'success';
	exit(0);
}
else {
	echo 'error';
	exit(0);
}
