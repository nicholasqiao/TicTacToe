<?php
/*
 * play.php
 * 
 * Main game page 
 * Pass game id with GET as 'gid'
 * example: play.php?gid=12
 *
 *  !! NOTE: assumes that a game has already been created in the database
 * involving the logged-in user.
 */
session_start();

require_once('../config.php');
require_once(ROOT . '/php/db/User.php');
require_once(ROOT . '/php/app/GameBoard.php');


if (!isset($_SESSION['uid'])) {
	header('Location: /index.html');
	exit(0);
}

else {
	try {
		$user = new User($_SESSION['uid']);
	}
	catch (NotFoundException $e) {
		error("play.php: user verification failed for uid " . $_SESSION['uid']);
		header('Location: /index.html');
		exit(0);
	}
}

$uid = $user->uid();

// check for valid game id
if (!isset($_REQUEST['gid'])) {
	echo '<!DOCTYPE HTML><html><head><title>Game Page: Error</title>
	      <body><p>Error: No game ID specified.</p>
	      <p><a href="main.html">Home Page</a></p>
	      </body></html>';
	exit(0);
}
	

try {
	$game = new GameBoard($_REQUEST['gid']);
}
catch (NotFoundException $e) {
	error("Invalid game ID in play.php: " . $_REQUEST['gid']);
	echo '<!DOCTYPE HTML><html><head><title>Game Page: Error</title>
      <body><p>Error: Invalid game ID specified.</p>
      <p><a href="main.html">Home Page</a></p>
      </body></html>';
	exit(0);
}

$gid = $_REQUEST['gid'];
$info = $game->gameInfo();

// if I'm user_one, I get to make a move
if ($info['uid_one'] == $uid) {
	$myMove = 'true'; // this is string because we use it later to set a JS variable
	$opp_uid = $info['uid_two'];
}
else if ($info['uid_two'] == $uid) {
	$myMove = 'false';
	$opp_uid = $info['uid_one'];
}

 /* Note: possible future functionality - wouldn't be hard to
 * change this such that you could spectate other games. */
else {
	echo '<!DOCTYPE HTML><html><head><title>Game Page: Error</title>
      	<body><p>Error: You are not participating in this match.</p>
      	<p><a href="main.html">Home Page</a></p>
      	</body></html>';
	exit(0);
}

try {
	$opponent = new User($opp_uid);
}
catch (NotFoundException $e) {
	error("A nonexistant user exists in current games, gid " . $gid);
	echo 'There has been an internal error.';
}

$myEmail = $user->getEmail();
$oppEmail = $opponent->getEmail();

echo '<!DOCTYPE HTML>
      <head> <title>Wombat Tic-Tac-Toe: Game</title>
      <script src="common/js/jquery.js"></script>
      <script src="common/js/game.js"></script>
      <link href="/common/style/play.css" type="text/css" rel="stylesheet">
      <script>
	myMove = ' . $myMove . ';
	uid = ' . $uid . ';
	gid = ' . $gid . ';
	window.onload = function (e) {
		getBoard();
		timer = window.setInterval(getBoard,500);
		if (myMove)
	 	 $("#turnIndicator").html("<h3>Your move!</h3>");
		else
		 $("#turnIndicator").html("<h3>Opponent\'s move!</h3>");
	}
      </script>
      </head>
      <body>
      <p><a href="/main.html">Home</a><p>
      <h3> ' . $myEmail . ' vs. ' . $oppEmail . '</h3>
      <div id="turnIndicator"></div>
      <div id="gameBoard"></div>
      <button type="button" id="resign" onclick="resign()">Resign</button>';
      
      if (!isset($_REQUEST['friend']))
      {
            echo '<button type="button" onclick="location=\'./common/addFriend.php?reqid=' . $opp_uid . '\'">Add to friends list</button>';
      }
      
	
