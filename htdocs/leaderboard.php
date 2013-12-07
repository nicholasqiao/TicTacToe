<?php
/* leaderboard.php
 * simply displays a list of the top 10 users
 */

require_once('../config.php');
require_once(ROOT . '/php/db/Game.php');

session_start();

if (isset($_SESSION['uid']))
	$uid = $_SESSION['uid'];
else
	$uid = 0;

$leaderboard = Game::getLeaderboard();

if (is_null($leaderboard)) 
	echo '<!DOCTYPE HTML><html><head><title>Leaderboard - error</title></head>
	      <body><p>There seems to be an error with the leaderboard. Please try again later. <a href="/main.html">Home</a></p></body></html>';
else {
	//table header
	$leaderTable = "<table>
			<tr>
			<th><b>Rank</b></th>
			<th><b>Email</b></th>
			<th><b>Wins</b></th>
			<th><b>Losses</b></th>
			<th><b>Ties</b></th>
			<th><b>Ratio</b><th>
			</tr>
			";


	$i = 1;


	foreach ($leaderboard as $row) {
		
		

		if ($row['uid'] == $uid)
			$tableRow = "<tr class='rowIsMe'>";
		else
			$tableRow = "<tr>";

		$tableRow .= "<td>" . $i     . "</td>" .
				"<td><a href='/userProfile.php?user=" . $row['uid'] . "'>". $row['username'] . "</a></td>" .
				"<td>" . $row['win']     . "</td>" .
				"<td>" . $row['loss']    . "</td>" .
				"<td>" . $row['tie']     . "</td>" . 
				"<td>" . $row['ratio']   . "</td></tr>"; 

			$tableRow = "<b>" . $tableRow . "</b>";

		$leaderTable .= $tableRow;


	$i++;
	}


	$leaderTable .= "</table>";


	echo '<!DOCTYPE HTML><html>
	      <head> <title>Leaderboard - Wombat TTT</title> </head>
	      <style type="text/css">
		 table .rowIsMe {
			font-weight:bold;
		 }
		</style>
	      <body>
	      <h1>Wombat TTT Leaderboard</h1>
	      <p><a href="/main.html">Home</a></p>
	      <div id="leaderboard">' . $leaderTable . '</div>
	      <p>Note: only users who have played a total of 10 or more games are displayed on the leaderboard.</p>
	      </body></html>';


} 
		


