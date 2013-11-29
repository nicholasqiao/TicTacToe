<?php
// declare all variables
$turn = 1;
$computer = 0;
$player = 0;
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

var_dump($board);

$a0 = $board[0][0];
$a1 = $board[0][1];
$a2 = $board[0][2];

$b0 = $board[1][0];
$b1 = $board[1][1];
$b2 = $board[1][2];

$c0 = $board[2][0];
$c1 = $board[2][1];
$c2 = $board[2][2];

echo($a0 . ' &nbsp;' . $a1 . ' &nbsp;' . $a2 . "<br>");
echo($b0 . ' &nbsp;' . $b1 . ' &nbsp;' . $b2 . "<br>");
echo($c0 . ' &nbsp;' . $c1 . ' &nbsp;' . $c2 . "<br>");

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

function detectBlocksAndWins() {
	$p = 0; // the other player
	if ($computer == 5) {
		$p == 3;
	} else {
		$p == 5;
	}

	$compWin = $computer * $computer;
	$playerWin = $p * $p;

	// brute force
	// check for wins for computer first - if one is found, return the position of the move to win
	// if no win for computer is found, check for wins for the player - if one is found return
	// the position of the move to block player's win
	// otherwise return random empty idx? (don't want to win every time so no checking for forks?)

	// could any of these 8 lines be wins this turn for the computer? 
	// or could any of the opponents lines be wins?
	if (($a0 * $a1 * $a2) == ($compWin)) {
		if ($a0 == 1) {
			$a0 = $computer;
		} elseif ($a1 == 1) {
			$a1 = $computer;
		} else {
			$a2 = $computer;
		}
	} elseif (($b0 * $b1 * $b2) == ($compWin)) {
		if ($b0 == 1) {
			$b0 = $computer;
		} elseif ($b1 == 1) {
			$b1 = $computer;
		} else {
			$b2 = $computer;
		}
	} elseif (($c0 * $c1 * $c2) == ($compWin)) {
		if ($c0 == 1) {
			$c0 = $computer;
		} elseif ($c1 == 1) {
			$c1 = $computer;
		} else {
			$c2 = $computer;
		}
	} elseif (($a0 * $b0 * $c0) == ($compWin)) {
		if ($a0 == 1) {
			$a0 = $computer;
		} elseif ($b0 == 1) {
			$b0 = $computer;
		} else {
			$c0 = $computer;
		}
	} elseif (($a1 * $b1 * $c1) == ($compWin)) {
		if ($a1 == 1) {
			$a1 = $computer;
		} elseif ($b1 == 1) {
			$b1 = $computer;
		} else {
			$c1 = $computer;
		}
	} elseif (($a2 * $b2 * $c2) == ($compWin)) {
		if ($a2 == 1) {
			$a2 = $computer;
		} elseif ($b2 == 1) {
			$b2 = $computer;
		} else {
			$c2 = $computer;
		}
	} elseif (($a0 * $b1 * $c2) == ($compWin)) {
		if ($a0 == 1) {
			$a0 = $computer;
		} elseif ($b1 == 1) {
			$b1 = $computer;
		} else {
			$c2 = $computer;
		}
	} elseif (($c0 * $b1 * $a2) == ($compWin)) {
		if ($c0 == 1) {
			$c0 = $computer;
		} elseif ($b1 == 1) {
			$b1 = $computer;
		} else {
			$a2 = $computer;
		}
	} elseif (($a0 * $a1 * $a2) == ($playerWin)) {
		if ($a0 == 1) {
			$a0 = $computer;
		} elseif ($a1 == 1) {
			$a1 = $computer;
		} else {
			$a2 = $computer;
		}
	} elseif (($b0 * $b1 * $b2) == ($playerWin)) {
		if ($b0 == 1) {
			$b0 = $computer;
		} elseif ($b1 == 1) {
			$b1 = $computer;
		} else {
			$b2 = $computer;
		}
	} elseif (($c0 * $c1 * $c2) == ($playerWin)) {
		if ($c0 == 1) {
			$c0 = $computer;
		} elseif ($c1 == 1) {
			$c1 = $computer;
		} else {
			$c2 = $computer;
		}
	} elseif (($a0 * $b0 * $c0) == ($playerWin)) {
		if ($a0 == 1) {
			$a0 = $computer;
		} elseif ($b0 == 1) {
			$b0 = $computer;
		} else {
			$c0 = $computer;
		}
	} elseif (($a1 * $b1 * $c1) == ($playerWin)) {
		if ($a1 == 1) {
			$a1 = $computer;
		} elseif ($b1 == 1) {
			$b1 = $computer;
		} else {
			$c1 = $computer;
		}
	} elseif (($a2 * $b2 * $c2) == ($playerWin)) {
		if ($a2 == 1) {
			$a2 = $computer;
		} elseif ($b2 == 1) {
			$b2 = $computer;
		} else {
			$c2 = $computer;
		}
	} elseif (($a0 * $b1 * $c2) == ($playerWin)) {
		if ($a0 == 1) {
			$a0 = $computer;
		} elseif ($b1 == 1) {
			$b1 = $computer;
		} else {
			$c2 = $computer;
		}
	} elseif (($c0 * $b1 * $a2) == ($playerWin)) {
		if ($c0 == 1) {
			$c0 = $computer;
		} elseif ($b1 == 1) {
			$b1 = $computer;
		} else {
			$a2 = $computer;
		}
	}
}

/*
a0 a1 a2
b0 b1 b2
c0 c1 c2
*/

function makeMove() {
	// if it's turn 1 and the computer goes first
	if (($turn == 1) && ($computer == 3)) {
		$a0 = $computer;
	} elseif ($turn == 2 && $computer == 5) { // if it's the second turn and it's the computer's turn
		if ($b1 = 1) {
			$b1 = $computer;
		} else {
			$a0 = $computer; // if center isn't free, take the upper left corner
		}
	} elseif(($turn ==3) && ($computer == 3)) { // if it's turn 3 and the computer went first
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

		}
	} else { 
		// if it's any turn but 1 or 2 (someone could win)
		// check if there's a way for the computer to win - if so make that move
		// check if there's a way for the player to win - if so make that move
		// check for moves that would create a fork for you - make that move (prob won't do this)
		detectBlocksAndWins();
	}
	return $board;
}
?>