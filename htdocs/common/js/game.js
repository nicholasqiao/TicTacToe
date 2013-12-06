/*
 * game.js
 * 
 * JavaScript functions relating to the game board
 */

var myMove;
var uid; //TODO javascript may have direct access to session variables, idk
var timer;
var gloBoard;

/*
 * Returns gameBoard array
 */
function getBoard() {
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
	var eImage = '<img src="/i/e.png">';


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


/*
 * Submit a move to the server
 * For now assume x and y are valid inputs - may add validation later
 */
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
			else if (xmlhttp.responseText == 'success'){
				return;
			}
			else { //for unexpected response
				console.log('unexpected response: ' + xmlhttp.responseText);
				return;
			}
		}
	}
	
	var requestStr = "/common/makeMove.php?gid= " + gid + "&uid= " + uid + "&row= " + x + "&col=" + y;

	xmlhttp.open("GET",requestStr,true);
	xmlhttp.send();
}

/* 
 * function attached to Resign button
 */
function resign() {
	if(!confirm('Are you sure you want to resign?'))
		return;

	
	submitResign();
 	
}

/*
 * Submit resignation helper method
 */
function submitResign() {
	var xmlhttp;

	if (window.XMLHttpRequest) // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();

	else // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{


			if (xmlhttp.responseText == 'error') {
				console.log('got error in resign');
				return;
			}
			else {
				getBoard();	
			}
		}
	}

	
	var requestStr = "/common/resign.php?gid= " + gid + "&uid= " + uid;

	xmlhttp.open("GET",requestStr,true);
	xmlhttp.send();
}


