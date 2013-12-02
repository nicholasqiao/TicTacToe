<?php
// declare all variables
$turn = 1;
$computer = 5; // computer is arbitrarily always O
$player = 3; // player always X
$winPossible = 0; // either 0, or the array index which results in a win
$winningNumber = 0; // calculated based on whether computer is x (9) or o (25)

// x goes first
// 1 = empty
// 3 = x
// 5 = o
// initialize the board
$board = array(
	    //0  1  2
	array(1, 1, 1),  // a
	array(1, 1, 1),  // b
	array(1, 1, 1)   // c
	);

/*
1 1 3
1 3 1
1 5 5

1 1 1
1 3 1
1 1 1

1 1 1
1 3 1
1 1 5
*/

//var_dump($board);

$a0 = $board[0][0];
$a1 = $board[0][1];
$a2 = $board[0][2];

$b0 = $board[1][0];
$b1 = $board[1][1];
$b2 = $board[1][2];

$c0 = $board[2][0];
$c1 = $board[2][1];
$c2 = $board[2][2];

//echo($a0 . ' &nbsp;' . $a1 . ' &nbsp;' . $a2 . "<br>");
//echo($b0 . ' &nbsp;' . $b1 . ' &nbsp;' . $b2 . "<br>");
//echo($c0 . ' &nbsp;' . $c1 . ' &nbsp;' . $c2 . "<br>");

// set the computer's number depending on which (x, o) was assigned to the player
// x, o should be assigned randomly each time, so it's not always one or the other
// going first
function setComputerAndPlayer($comp, $play) {
	$computer = $comp;
	$player = $play;
}

/* detectBlocksAndWins -
*  checks for possible wins for computer and makes the first winning move it finds OTHERWISE 
*  checks for possible wins for player and makes the blocking move it finds -
*  these are the only two possible scenarios as the others are taken care of before calling
*  this method
*/

// board expected in integer form
function detectBlocksAndWins($board) {
	$computer = 5;
	$player = 3;

	$a0 = $board[0][0];
	$a1 = $board[0][1];
	$a2 = $board[0][2];

	$b0 = $board[1][0];
	$b1 = $board[1][1];
	$b2 = $board[1][2];

	$c0 = $board[2][0];
	$c1 = $board[2][1];
	$c2 = $board[2][2];

	$compWin = $computer * $computer;
	$playerWin = $player * $player;
	$moveMade = false;

	// brute force
	// check for wins for computer first - if one is found, return the position of the move to win
	// if no win for computer is found, check for wins for the player - if one is found return
	// the position of the move to block player's win
	// otherwise return random empty idx? (don't want to win every time so no checking for forks?)

	// could any of these 8 lines be wins this turn for the computer? 
	// or could any of the opponents lines be wins?
	if (($board[0][0] * $board[0][1] * $board[0][2]) == ($compWin)) {
		if ($board[0][0] == 1) {
			$board[0][0] = $computer;
			$moveMade = true;
		} elseif ($board[0][1] == 1) {
			$board[0][1] = $computer;
			$moveMade = true;
		} else {
			$board[0][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[1][0] * $board[1][1] * $board[1][2]) == ($compWin)) {
		if ($board[1][0] == 1) {
			$board[1][0] = $computer;
			$moveMade = true;
		} elseif ($board[1][1] == 1) {
			$board[1][1] = $computer;
			$moveMade = true;
		} else {
			$board[1][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[2][0] * $board[2][1] * $board[2][2]) == ($compWin)) {
		if ($board[2][0] == 1) {
			$board[2][0] = $computer;
			$moveMade = true;
		} elseif ($board[2][1] == 1) {
			$board[2][1] = $computer;
			$moveMade = true;
		} else {
			$board[2][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][0] * $board[1][0] * $board[2][0]) == ($compWin)) {
		if ($board[0][0] == 1) {
			$board[0][0] = $computer;
			$moveMade = true;
		} elseif ($board[1][0] == 1) {
			$board[1][0] = $computer;
			$moveMade = true;
		} else {
			$board[2][0] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][1] * $board[1][1] * $board[2][1]) == ($compWin)) {
		if ($board[0][1] == 1) {
			$board[0][1] = $computer;
			$moveMade = true;
		} elseif ($board[1][1] == 1) {
			$board[1][1] = $computer;
			$moveMade = true;
		} else {
			$board[2][1] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][2] * $board[1][2] * $board[2][2]) == ($compWin)) {
		if ($board[0][2] == 1) {
			$board[0][2] = $computer;
			$moveMade = true;
		} elseif ($board[1][2] == 1) {
			$board[1][2] = $computer;
			$moveMade = true;
		} else {
			$board[2][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][0] * $board[1][1] * $board[2][2]) == ($compWin)) {
		if ($board[0][0] == 1) {
			$board[0][0] = $computer;
			$moveMade = true;
		} elseif ($board[1][1] == 1) {
			$board[1][1] = $computer;
			$moveMade = true;
		} else {
			$board[2][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[2][0] * $board[1][1] * $board[0][2]) == ($compWin)) {
		if ($board[2][0] == 1) {
			$board[2][0] = $computer;
			$moveMade = true;
		} elseif ($board[1][1] == 1) {
			$board[1][1] = $computer;
			$moveMade = true;
		} else {
			$board[0][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][0] * $board[0][1] * $board[0][2]) == ($playerWin)) {
		if ($board[0][0] == 1) {
			$board[0][0] = $computer;
			$moveMade = true;
		} elseif ($board[0][1] == 1) {
			$board[0][1] = $computer;
			$moveMade = true;
		} else {
			$board[0][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[1][0] * $board[1][1] * $board[1][2]) == ($playerWin)) {
		if ($board[1][0] == 1) {
			$board[1][0] = $computer;
			$moveMade = true;
		} elseif ($board[1][1] == 1) {
			$board[1][1] = $computer;
			$moveMade = true;
		} else {
			$board[1][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[2][0] * $board[2][1] * $board[2][2]) == ($playerWin)) {
		if ($board[2][0] == 1) {
			$board[2][0] = $computer;
			$moveMade = true;
		} elseif ($board[2][1] == 1) {
			$board[2][1] = $computer;
			$moveMade = true;
		} else {
			$board[2][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][0] * $board[1][0] * $board[2][0]) == ($playerWin)) {
		if ($board[0][0] == 1) {
			$board[0][0] = $computer;
			$moveMade = true;
		} elseif ($board[1][0] == 1) {
			$board[1][0] = $computer;
			$moveMade = true;
		} else {
			$board[2][0] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][1] * $board[1][1] * $board[2][1]) == ($playerWin)) {
		if ($board[0][1] == 1) {
			$board[0][1] = $computer;
			$moveMade = true;
		} elseif ($board[1][1] == 1) {
			$board[1][1] = $computer;
			$moveMade = true;
		} else {
			$board[2][1] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][2] * $board[1][2] * $board[2][2]) == ($playerWin)) {
		if ($board[0][2] == 1) {
			$board[0][2] = $computer;
			$moveMade = true;
		} elseif ($board[1][2] == 1) {
			$board[1][2] = $computer;
			$moveMade = true;
		} else {
			$board[2][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[0][0] * $board[1][1] * $board[2][2]) == ($playerWin)) {
		if ($board[0][0] == 1) {
			$board[0][0] = $computer;
			$moveMade = true;
		} elseif ($board[1][1] == 1) {
			$board[1][1] = $computer;
			$moveMade = true;
		} else {
			$board[2][2] = $computer;
			$moveMade = true;
		}
	} elseif (($board[2][0] * $board[1][1] * $board[0][2]) == ($playerWin)) {
		if ($board[2][0] == 1) {
			$board[2][0] = $computer;
			$moveMade = true;
		} elseif ($board[1][1] == 1) {
			$board[1][1] = $computer;
			$moveMade = true;
		} else {
			$board[0][2] = $computer;
			$moveMade = true;
		}
	}



	// if the computer didn't find a move to make, make a move in a random empty square
	if (!$moveMade) {
		error("debug: didn't find a blocking move in ai.php");
		$board = randomMove($board);	
		
	}

	return $board;

}

/*
 * returns a board where the computer has made a randomly selected move. 
 */
function randomMove($board) {
		$computer = 5;

		$emptySquares = -1;
		$emptyIndices = array(9);
		for ($i = 0; $i<3; $i++)
			for ($j=0; $j<3; $j++)
				if ($board[$i][$j] == 1) {
					$emptySquares++;
					$emptyIndices[$emptySquares] = array(2);
					$emptyIndices[$emptySquares]['i'] = $i;
					$emptyIndices[$emptySquares]['j'] = $j;

				}

		// choose random index
		$random = rand(0,$emptySquares);
		
		// make the move
		$moveI = $emptyIndices[$random]['i'];
		$moveJ = $emptyIndices[$random]['j'];
		$board[$moveI][$moveJ] = $computer;
		return $board;

}

/* same as above, but takes a character board and returns one */
function randomMoveStr($boardChar) {
	$boardInt = boardChartoInt($boardChar);

	$boardInt = randomMove($boardInt);

	return boardInttoChar($boardInt);

}

/*
a0 a1 a2
b0 b1 b2
c0 c1 c2
*/

/*
 * MG: 
 *   -renamed because I already have a makeMove
 */
       
function computerMove($boardChar) {
	$computer = 5;
	$player = 3;

	$board = boardChartoInt($boardChar);

	$turn = 1;
	for ($i = 0; $i<3; $i++)
		for ($j=0;$j<3;$j++)
			if ($board[$i][$j] != 1)
				$turn++;


	
	
	// if it's turn 1
	if (($turn == 1)) {
		$board[0][0] = $computer;
	} elseif ($turn == 2) { // if it's the second turn and it's the computer's turn
		if ($board[1][1] == 1) {
			$board[1][1] = $computer;
		} else {
			$board[0][0] = $computer; // if center isn't free, take the upper left corner
		}
	/* }  elseif($turn ==3) { // if it's turn 3 and the computer went first
		if ($b1 = 1) {
			$b1 = $computer;
		} else {
			// randomly pick between $a1 or $b0
			$rand = rand(0, 1);
			if ($rand == 1) {
				$a1 = $computer;
			} else {
				$b0 = $computer;
			}

		} */
	} else { 
		// if it's any turn but 1 or 2 (someone could win)
		// check if there's a way for the computer to win - if so make that move
		// check if there's a way for the player to win - if so make that move
		// check for moves that would create a fork for you - make that move (prob won't do this)
		$board = detectBlocksAndWins($board);
	}
	return boardInttoChar($board);
}

/*
 * assumes player is always X, so player = 3, computer = 5
 */
function boardChartoInt ($boardChar) {
	$boardInt = array(3);
	$boardInt[0] = array(3);
	$boardInt[1] = array(3);
	$boardInt[2] = array(3);

	for ($i = 0;$i<3;$i++)
		for($j=0;$j<3;$j++) {
			if ($boardChar[$i][$j] == 'X')
				$boardInt[$i][$j] = 3;
			else if ($boardChar[$i][$j] == 'O')
				$boardInt[$i][$j] = 5;
			else //catch 'E' or invalid input and make it empty
				$boardInt[$i][$j] = 1;
		}

	return $boardInt;
}

function boardInttoChar ($boardInt) {
	$boardChar = array(3);
	$boardChar[0] = array(3);
	$boardChar[1] = array(3);
	$boardChar[2] = array(3);

	for ($i = 0;$i<3;$i++)
		for($j=0;$j<3;$j++) {
			if ($boardInt[$i][$j] == 3)
				$boardChar[$i][$j] = 'X';
			else if ($boardInt[$i][$j] == 5)
				$boardChar[$i][$j] = 'O';
			else //catch 1 or invalid number and make it empty
				$boardChar[$i][$j] = 'E';
		}

	return $boardChar;
}


?>
