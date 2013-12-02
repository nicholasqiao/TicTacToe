<?php
/* 
 * Script for playing against AI.
 *
 * Passed a board state and a user's move to make to it through GET.
 *  Assesses the state of the game and if necessary, gets an AI move given
 * the new board state.
 *
 * Returns a board state using JSON.
 */

require_once '../../config.php';
require_once ROOT . '/php/app/PracticeGame.php';


if (!isset($_REQUEST['board']) || !isset($_REQUEST['row']) || !isset($_REQUEST['col'])) {
	echo 'error';
	error("makePracticeMove.php called with invalid arguments.");
	exit(0);
}

$board = PracticeGame::stringToArr($_REQUEST['board']);
$row = $_REQUEST['row'];
$col = $_REQUEST['col'];

if (!isset($_REQUEST['diff']))
	$diff = 'easy';
else
	$diff = $_REQUEST['diff'];

if (is_null($board)) 
	echo 'error';


$result = PracticeGame::makeMove($board,$row,$col,$diff);

if (is_null($result))
	echo 'error';
else
	echo JSON_encode($result);
