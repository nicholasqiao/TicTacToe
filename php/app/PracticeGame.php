<?php



require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(ROOT . '/php/app/ai.php');

class PracticeGame {

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
	

	// makes move, returns an array like
	// $result['board'] = array of gamebaord
	// $result['status'] is {'userWin','computerWin','tie'}
	static function makeMove($board, $row, $col, $diff) {
		// validate row, col
		if ($row < 0 || $row > 2)
			return null;
		if ($col < 0 || $col > 2)
			return null;


		//check if we're trying to make a move in a non-empty position
		if ($board[$row][$col] == 'X' || $board[$row][$col] == 'O')
			return null;

		// user is always X
		$board[$row][$col] = 'X';
		
		$finishStatus = PracticeGame::checkIfFinalMove($board,$row,$col,'X');

		if ($finishStatus == 'win')  {
			$result = array(2);
			$result['board'] = $board;
			$result['status'] = "userWin";
			return $result;
		}	
		if ($finishStatus == 'tie') {
			$result = array(2);
			$result['board'] = $board;
			$result['status'] = "tie";
			return $result;
		}

		// if we make it here, then the computer gets to make a move


		if ($diff == 'hard')
			$newBoard = computerMove($board);
		else if ($diff == 'medium') { // medium: be smart half of the time
			$rand = rand(0,1);
			if ($rand == 0) {
				$newBoard = computerMove($board);
			}
			else {
				$newBoard = randomMoveStr($board);
			}
		}
		else
			$newBoard = randomMoveStr($board);	

		$moveMade = false;

		for ($i=0;$i<3;$i++)
			for ($j=0;$j<3;$j++)
 				if ($newBoard[$i][$j] != $board[$i][$j]) {
					$computerI = $i;
					$computerJ = $j;
					$moveMade = true;
				}

		$board = $newBoard;

		if (!$moveMade) {
			error("computer didn't make a move!!!!!!!!");
			return null;
		}


		$computerFinishStatus = PracticeGame::checkIfFinalMove($board,$computerI,$computerJ,'O');
		if ($computerFinishStatus == 'win')  {
			$result = array(2);
			$result['board'] = $board;
			$result['status'] = "computerWin";
			return $result;
		}	
		if ($computerFinishStatus == 'tie') {
			$result = array(2);
			$result['board'] = $board;
			$result['status'] = "tie";
			return $result;
		}
		
		$result = array(2);
		$result['board'] = $board;
		$result['status'] = "continue";

		return $result;
		
	}

	public static function checkIfFinalMove($boardArr, $row, $col, $mark) {
	/*debug ob_start();
		var_dump($boardArr);
		error(ob_get_contents());
		ob_end_clean();
		error("row: " . $row . ",col: " . $col . ",mark: " . $mark); */
		

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
