<?php
/*
 * practice.php
 * pass diff={easy,hard} to indicate desired difficulty
 */

if (!isset($_REQUEST['diff'])) 
	$diff = 'easy';


if ($_REQUEST['diff'] != 'hard' && $_REQUEST['diff'] != 'medium') {
	$diff = 'easy';
	$diffStr = "Easy";
}
else 
	$diff = $_REQUEST['diff'];

echo '<!DOCTYPE HTML>
<html>
<head> <title>Wombat Tic Tac Toe - Practice</title>

<link href="/common/style/play.css" type="text/css" rel="stylesheet">

<script>
	var diff = \'' . $diff . '\'
</script>
<script src="/common/js/jquery.js"></script>
<script src="/common/js/ai_game.js"></script>

</head>

<body>
<p><a href="/main.html">Home</a></p>
<p><a href="/levelpick.html">Change Difficulty</a></p>
<p>Difficulty: ' . ucfirst($diff) . '
<div id="turnIndicator"></div>
<div id="gameBoard"></div>
</body>
</html>';
