/*
 * Follows the same general structure as game.js , but with modifications that
 * allow for AI gameplay such as only keeping the gameboard locally and not
 * constatly querying the DB, and sending moves to AI engine instead of DB.
 */

// intialize game board

var gloBoard; // the game board
var myMove; // is it the user's turn? (bool)
var userFirst;

$(document).ready(function() {
	myMove = true; // user always gets the first move in the first game
	userFirst = true; // used for playAgain

	board = emptyBoard();

	drawBoard(board,myMove);

	gloBoard = board;

	
});

function emptyBoard() {
	board = new Array();
	board[0] = new Array();
	board[1] = new Array();
	board[2] = new Array();

	for (i = 0; i < 3; i++)
		for (j=0;j<3;j++)
			board[i][j] = 'E';

	return board;
}

/*
 * x = -1, y = -1 specifies the computer is making the first move
 */
function makeMove(x,y) {
	var xmlhttp;

	myMove = false; // go ahead and set this to false; if there was an error it will be changed back next time getBoard() is called
	$("#turnIndicator").html("<h3>Computer's move.</h3>");
	drawBoard(addMove(gloBoard,x,y), false);

	if (window.XMLHttpRequest) // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();

	else // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if (xmlhttp.responseText == 'error') {
				console.log('got error in makeMove');
				return; // do nothing, the user will no
			}
			else {
				responseArr = $.parseJSON(xmlhttp.responseText);
				gloBoard=responseArr['board'];
				gameStatus = responseArr['status'];

				var playAgainStr = '<button id="playAgain" type="button" onclick="playAgain()">Play Again!</button>';

				if (gameStatus == 'computerWin') {
					$("#turnIndicator").html("<h3>Computer wins.</h3>" + playAgainStr);
					drawBoard(gloBoard,false);
				}

				else if (gameStatus == 'userWin') {
					$("#turnIndicator").html("<h3>You win!</h3>" + playAgainStr);
					drawBoard(gloBoard,false);
				}
				else if (gameStatus == 'tie') {
					$("#turnIndicator").html("<h3>Cat's game</h3>" + playAgainStr);
					drawBoard(gloBoard,false);
				}
				else {
					myMove = true;
					$("#turnIndicator").html("<h3>Your turn!</h3>");
					drawBoard(gloBoard,myMove);
				}
				return;
			}
		}
			
	}

	sendBoard = boardToStr(gloBoard);
	
	var requestStr = "/common/makePracticeMove.php?board=" + sendBoard + "&row=" + x + "&col=" + y  + "&diff=" + diff;

	xmlhttp.open("GET",requestStr,true);
	xmlhttp.send();
}

function boardToStr(board) {
	var boardStr = '';

	for(var i = 0; i < 3 ; i++)
		for (var j = 0; j < 3; j++)
			boardStr += board[i][j];

	return boardStr;
}

/*
 * Draw the game board, given an array of X's and O's
 *  This array should be recieved from PHP in JSON format and already 
 * converted to JS array before passing to this fxn
 * 
 * We're going to loop through this array and build an HTML table and return it
 *
 * Also passed a boolean myMove - if true, the empty tiles will be clickable 
 */
function genBoard(arr, isMyMove) {
	var xImage = '<img src="/i/x.png">';
	var oImage = '<img src="/i/o.png">';
	var eImage = '<a href="#"><img src="/i/e.png"></a>';


	// html is the string that holds the html for the table
	var html = "<table>";

	for (var i = 0; i < 3; i++) {
		html += "<tr>";

		for (var j = 0; j < 3; j++) {
			html += "<td>";

			var mark = arr[i][j];
			if (mark == 'X')
				html += xImage;
			else if (mark == 'O')
				html += oImage;
			else {
				if (isMyMove) {
					html += '<a href="#" onclick="makeMove(' + i + ',' + j + ');"><img src="/i/e.png"></a>';
				}
				else 
					html += eImage;
			}

			html += "</td>";
		}

		html += "</tr>";
	}

	html += "</table>";

	return html;
}

function drawBoard(arr, isMyMove) {
	html = genBoard(arr, isMyMove);
	$('#gameBoard').html(html);	
}



function playAgain() {
	if ($("#playerAlwaysFirst").prop("checked"))
		userFirst = true;
	else
		userFirst = !userFirst;

	myTurn = userFirst;

	gloBoard = emptyBoard();

	if (myTurn) {
		drawBoard(gloBoard,myTurn);
		$("#turnIndicator").html("<h3>Your move!</h3>");
	}
	else {
		$("#turnIndicator").html("<h3>Computer's turn.</h3>");
		makeMove(-1,-1);
	}
}

function addMove(board,x,y) {
	if (x < 0 || y < 0)
		return board;

	var clone = new Array();
	clone[0] = new Array();
	clone[1] = new Array();
	clone[2] = new Array();

	for (var i=0; i<3; i++)
		for (var j=0;j<3;j++)
			clone[i][j] = board[i][j];
			

	clone[x][y] = 'X';
	return clone;
}

