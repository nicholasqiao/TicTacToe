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
       /* $gameState = $arr['board'];
        $firstRow = $gameState[0];
        $secondRow = $gameState[1];
        $thirdRow = $gameState[2];
        echo $firstRow[0].$firstRow[1].$firstRow[2].$secondRow[0].$secondRow[1].$secondRow[2].$thirdRow[0].$thirdRow[1].$thirdRow[2];*/
}
	


