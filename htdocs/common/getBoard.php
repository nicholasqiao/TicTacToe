<?php 

/*
 * returns JSON encoded game board
 * pass desired game ID with GET
 * getBoard.php?gid=12
 */

require_once '../../config.php';
require_once ROOT . '/php/app/GameBoard.php';

session_start();
if (!isset($_SESSION['gid'])) {
	echo 'gid doesnt exist';
}
else {
	$arr = GameBoard::getState($_SESSION['gid']);
	if (is_null($arr))
		echo 'getState error';
	else
        //$gameState = JSON_encode($arr);
        $gameState = $arr['board'];
        $firstRow = $gameState[0];
        $secondRow = $gameState[1];
        $thirdRow = $gameState[2];
        echo $firstRow[0].$firstRow[1].$firstRow[2].$secondRow[0].$secondRow[1].$secondRow[2].$thirdRow[0].$thirdRow[1].$thirdRow[2];
}
	


