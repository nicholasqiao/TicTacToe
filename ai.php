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
$board = array(
	    //0  1  2
	array(2, 1, 1),  // a
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

// set the computer's number depending on which (x, o) was assigned to the player
// x, o should be assigned randomly each time, so it's not always one or the other
// going first
function setComputerAndPlayer($comp, $play) {
	$computer = $comp;
	$player = $play;
}

/*
Posswin(p) : returns 0 if player p can’t win on his next move;
otherwise it returns the number of square that constitutes a winning move.
This function will enable program both to win and to block opponents win.
This function operates by checking each of the rows, columns and diagonals.
By multiplying the values of its square together for an entire row 
(or column or diagonal), the possible situation of win can be checked.
If the product be 18 (3 x 3 x 2), then X can win. If the product is 50 (5 x 5 x 2),
then O can win. If a winning row (column or diagonal) is found,
the blank square in it can be deter mined and the number of that square
is returned by this function.
*/

function possibleWin() {
	$p = 0; // the other player
	if ($computer == 5) {
		$p == 3;
	} else {
		$p == 5;
	}
	// brute force
	// check for wins for computer - if one is found, return the position of the move to win
	// if no win for computer is found, check for wins for the player - if one is found return
	// the position of the move to block player's win
	// otherwise return random empty idx? (don't want to win every time so no checking for forks?)

	// could any of these 8 lines be wins this turn for the computer? 
	// or could any of the opponents lines be wins?
	if (($a0 * $a1 * $a2) == ($computer * $computer)) {
		if ($)
		if ($a0 == 1) {
			return $
		}
	} elseif () {

	} elseif ()

	//(($a0 * $a1 * $a2) == ($p * $p)))
	return 0;
}

function makeMove() {
	// if it's turn 1
	if ($turn == 1) {
		// if computer (x) goes first, make a move in the upper left corner $a0
		if ($computer == "x") {
			$a0 = $computer;
		} else { // if computer (o) goes second, check if center $b1 is free
			if ($b1 = 1) {
				$b1 = $computer;
			} else { // if center isn't free, make a move in upper left corner $a0
				$a0 = $computer;
			}
		}
	} else { // if it's any turn but 1 (someone could win)
		// check if there's a way for the computer to win - if so make that move
		// check if there's a way for the player to win - if so make that move
		// check for moves that would create a fork for you - make that move
	}

}

?>