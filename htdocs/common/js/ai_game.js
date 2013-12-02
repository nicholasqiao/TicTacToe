/*
 * Follows the same general structure as game.js , but with modifications that
 * allow for AI gameplay such as only keeping the gameboard locally and not
 * constatly querying the DB, and sending moves to AI engine instead of DB.
 */

// intialize game board

var gloBoard; // the game board
var myMove; // is it the user's turn? (bool)

$(document).ready(function() {
	myMove = true; // user always gets the first move in the first game

	//create an empty board
	board = new Array();
	board[0] = new Array();
	board[1] = new Array();
	board[2] = new Array();

	for (i = 0; i < 3; i++)
		for (j=0;j<3;j++)
			board[i][j] = 'E';

	drawBoard(board,myMove);

	gloBoard = board;

	
});

function emptyBoard() {

}


/*function getBoard() {
	var xmlhttp;

	if (window.XMLHttpRequest) // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();

	else // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if (xmlhttp.responseText == 'error') {
				console.log('got error in getBoard');
				return;
			}
			else {
				console.log(xmlhttp.responseText);
				var array = $.parseJSON(xmlhttp.responseText);

				console.log(array);

				var board = array["board"];

				var winner = array["winner"];

				if (winner!=null) {
					window.clearInterval(timer);

				//	var playAgainStr = '<h3><a href="/common/playAgain.php?gid=' + gid + '">Play Again</a></h3>';
					var playAgainStr = ' ';	
					if (winner == uid)
						$("#turnIndicator").html("<h3>You won!</h3>" + playAgainStr);
					else if (winner == 0)
						$("#turnIndicator").html("<h3>Cat's game</h3>" + playAgainStr);
					else
						$("#turnIndicator").html("<h3>You lost</h3><img src='/i/badman.jpg'>" + playAgainStr);
					drawBoard(board,false);
					return;
			
				}

				gloBoard = array["board"]; // save last board 
				var turn = array["turn"];

				if (turn == uid) {
					myMove = true;
					$("#turnIndicator").html("<h3>Your move!</h3>");
				}
				else {
					myMove = false;
					$("#turnIndicator").html("<p>Opponent's turn</p>");
				}
			
	
				drawBoard(board,myMove);
			}
		}
	}

	var requestStr = "/common/getBoard.php?gid=" + gid;

	xmlhttp.open("GET",requestStr,true);
	xmlhttp.send();
}*/

function makeMove(x,y) {
	var xmlhttp;

	myMove = false; // go ahead and set this to false; if there was an error it will be changed back next time getBoard() is called
	drawBoard(gloBoard, false);

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

				if (gameStatus == 'computerWin') {
					$("#turnIndicator").html("<h3>Computer wins.</h3>");
					drawBoard(gloBoard,false);
				}

				else if (gameStatus == 'userWin') {
					$("#turnIndicator").html("<h3>You win!</h3>");
					drawBoard(gloBoard,false);
				}
				else if (gameStatus == 'tie') {
					$("#turnIndicator").html("<h3>Cat's game</h3>");
					drawBoard(gloBoard,false);
				}
				else {
					myMove = true;
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

function playAgain(computerFirst) {
	
}

