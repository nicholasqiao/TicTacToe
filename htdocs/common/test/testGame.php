<?php

require_once '../../../config.php';
require_once ROOT . '/php/db/Game.php';

echo '<!DOCTYPE HTML>

<html>
	<head>
		<title>Test Harness for Database Operations</title>
	</head>
<body>';

/* Game + game state functions

if ( ($gid = Game::newGame(1,2,"test")) < 0 )
	exit("<p>Error creating a new game.<p>");
else 
	echo "<p>Created a new game with game ID " . $gid . ". </p>";

if ( is_null ($state =  Game::getstate($gid)) )
	exit("<p>Error accessing game state.</p>");
else
	echo "<p>" . $state . "</p>";

if ( !Game::updateState($gid,"updated state") )
	exit("<p>Error updating game state.</p>");

if ( is_null ($state = Game::getstate($gid)) )
	exit("<p>Error accessing game state.</p>");
else
	echo "<p>" . $state . "</p>";
*/


/* Queue + dequeue 

Game::enQ(2);
Game::enQ(1);
$deq= Game::deQ();

echo $deq;
*/

/* Game request functions
Game::gameRequest(1,2);
Game::gameRequest(3,2);
var_dump(Game::getRequests(2));
echo '<br>';
var_dump(Game::myRequests(1));
echo '<br>';
echo Game::removeRequest(1,2);
echo Game::removeRequest(3,2);
*/




echo '</body></html>';

?>
