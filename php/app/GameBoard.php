<?php
/* GameBoard.php
 * Game logic and such
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(ROOT . '/php/db/Game.php');
require_once(ROOT . '/php/db/User.php');


class GameBoard
{
	private $gid;
	private $gameInfo;

	function __construct($gid) {
		$this->gid = $gid;

		$this->gameInfo = Game::info($this->gid);
		if (is_null($this->gameInfo))
			throw new NotFoundException();

	}

	function gameInfo() {
		return $this->gameInfo;
	}	

	/* 
	 * takes a game state from the database and returns an array
	 *
	 * returns null for error such as invalid game id
         * expected string format is AAAAAAAAA (9 characters)
	 * each set of 3 A's is a row, so the string specifies 
	 * [row 0][row 1][row 2]
	 * where A = {X,O,E}
         * X - x placed
	 * O - o placed
 	 * E - empty
	 */
	static function getState($gid) {
		$state = Game::getState($gid);

		$stateStr = $state['game_state'];

		if (is_null($stateStr)) {
			error("GameBoard::getState(): null string recieved from Game::getState()");
			return null;
		}

		if (strlen($stateStr) != 9) {
			error("GameBoard::getState(): invalid length string retrieved from database: " . $stateStr);
			
			return null;
		}

	
		
		$boardArr = GameBoard::stringToArr($stateStr);

		$stateArr = array(3);
		$stateArr['board'] = $boardArr;
		$stateArr['turn'] = $state['turn'];
		$stateArr['winner'] = $state['winner'];

		return $stateArr;
	}
		

		

	/*
	 * converts given array to string and inserts into database
	 * Returns false for error
	 *
	function updateState($gid, $stateArr) {
		$stateStr = "";

		for ($i = 0; $i < 9; $i++) {
			$row = (int)($i / 3);
			$col = $i % 3;

			if (strcmp($stateStr[$i],'X') && strcmp($stateStr[$i],'O') && strcmp($stateStr[$i],'E')) {
				error("GameBoard::updateState(): Invalid state in given array: stateArr[" . $row . "][" . $col . "] = " . $stateArr[$row][$col]);
				return null;
			}
			
			$stateStr = $stateStr . $stateArr[$row][$col];
		}
		
		return Game::updateState($gid,$stateStr);
		
	} */

	/*
 	 *  Arbitrarily make the user in 'uid_one' column from database X
 	 * and 'uid_two' O.
 	 *
	 * Pass the desired move and the user that's making the move.
	 * Move syntax: row,column in {0,1,2}
 	 *
 	 *  reference:
	 *
	 *  | (0,0) | (0,1) | (0,2) |
	 *  |-------|-------|-------|
	 *  | (1,0) | (1,1) | (1,2) |
	 *  |-------|-------|-------|
	 *  | (2,0) | (2,1) | (2,2) |
	 *
	 * Returns true on success, false for error
	 *  Error may include user not allowed to access this game, 
	 * or user tried to make a move where there is already an X or O
 	 *
	 * Static function because it is invoked in a separate script
	 */
	static function makeMove($gid, $uid, $row, $col) {
		// validate row, col
		if ($row < 0 || $row > 2)
			return false;
		if ($col < 0 || $col > 2)
			return false;

		$info = Game::info($gid);
	
		if ($uid == $info['uid_one']) {
			$mark = 'X';
			$nextTurn = $info['uid_two'];
		}
		else if ($uid == $info['uid_two']) {
			$mark = 'O';
			$nextTurn = $info['uid_one'];
		}
		else
			return false; //somebody submitted a move to a game they're not in
		
		/* this is the position in the string that respresents game state
	 	 * that we're going to try to change. we know the format of the string is
	 	 * [row 0][row 1][row 2] so we're going to do $row * 3 to put us at the 0 column
		 * of row 0, 1, or 2 in the string,and then add $col to that value to put us in the
		 * correct column position. sorry if that is cryptic
		 */ 
		$targetStringPos = ($row * 3) +  $col;

		// we're going to get the string state directly and update it
		$state = Game::getState($gid);
		$stateStr = $state['game_state'];		

		if (is_null($stateStr))
			return false;

		//check if we're trying to make a move in a non-empty position
		if ($stateStr[$targetStringPos] == 'X' || $stateStr[$targetStringPos] == 'O')
			return false;

		$stateStr[$targetStringPos] = $mark; // make the move

		$finishStatus = GameBoard::checkIfFinalMove($stateStr,$row,$col,$mark);

		if ($finishStatus == 'win')  {
			User::recordWin($uid);
			User::recordLoss($nextTurn);
			Game::finish($gid,$uid);
			return Game::updateState($gid,$stateStr,$nextTurn);
		}	
		if ($finishStatus == 'tie') {
			User::recordTie($uid);
			User::recordTie($nextTurn);
			Game::finish($gid,0);
			return Game::updateState($gid,$stateStr,$nextTurn);
		}

		return Game::updateState($gid,$stateStr,$nextTurn);
		
	}

	/*
	 * converts a given game state string to array format
	 */	
	public static function stringToArr($stateStr) {
		$boardArr = array(3);
		$boardArr[0] = array(3);
		$boardArr[1] = array(3);	
		$boardArr[2] = array(3);


		/* use mod and int division to enumerate the array
	   	 * row by row
		 */
		for($i = 0; $i < 9; $i++) {
			if (strcmp($stateStr[$i],'X') && strcmp($stateStr[$i],'O') && strcmp($stateStr[$i],'E')) {
				error("GameBoard::stringToArr():Invalid state string passed: " . $stateStr);
				return null;
			}

			$row = (int)($i / 3);
			$col = $i % 3;

			$boardArr[$row][$col] = $stateStr[$i];
		}

		return $boardArr;
	}

	/*
	 *  This function takes a state string and determines whether either a win or tie
	 * has occured after making the specified move. It returns the string 'win' to indicate a winning move
	 * and 'tie' to indicate that the last possible move was made but it was not a winning one. 
	 * 
	 * Returns 'continue' for a non-finished game
	 */
	public static function checkIfFinalMove($stateStr, $row, $col, $mark) {
		ob_start();
		$boardArr = GameBoard::stringToArr($stateStr);
		var_dump($boardArr);
		
		error(ob_get_contents());
		error('row: ' . $row);
		error('col: ' . $col);
		error('mark: ' . $mark);
		
		//check the row the move was made in for a complete row
		if (($boardArr[(int)$row][0] == $boardArr[(int)$row][1]) && ($boardArr[(int)$row][1] == $boardArr[(int)$row][2]) && ($boardArr[(int)$row][2] == $mark))
			return 'win'; 

		//same for columns
		if (($boardArr[0][(int)$col] == $boardArr[1][(int)$col]) && ($boardArr[1][(int)$col] == $boardArr[2][(int)$col]) && ($boardArr[2][(int)$col] == $mark))
			return 'win';

		//check both diagonals
		if (($boardArr[0][0] == $boardArr[1][1]) && ($boardArr[1][1] == $boardArr[2][2]) && ($boardArr[2][2] == $mark))
			return 'win';

		if (($boardArr[0][2] == $boardArr[1][1]) && ($boardArr[1][1] == $boardArr[2][0]) && ($boardArr[2][0] == $mark))
			return 'win';

		$tie = ($boardArr[0][0] != 'E');

		//check for tie
		for ($i = 0 ; $i < 3; $i++)
			for ($j = 0; $j < 3; $j++)
				$tie = $tie && ($boardArr[$i][$j] != 'E');

		if ($tie)
			return 'tie';

		return 'continue';
					
	}

}
