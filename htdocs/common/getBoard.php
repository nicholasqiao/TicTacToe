<?php 

/*
 * returns JSON encoded game board
 * pass desired game ID with GET
 * getBoard.php?gid=12
 */

require_once '../../config.php';
require_once ROOT . '/php/app/GameBoard.php';

if (!isset($_REQUEST['gid'])) {
	echo 'error';
}
else {
	$arr = GameBoard::getState($_REQUEST['gid']);
	if (is_null($arr))
		echo 'error';
	else
		echo JSON_encode($arr);
}
	


