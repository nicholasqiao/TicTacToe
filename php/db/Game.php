<?php 

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(ROOT . '/php/db/Model.php');

class Game
{
	/*
 	 * Helper function that executes input statment $stmt.
	 * Cleans up the code below a little bit; allows for easier error logging.
	 */
	private static function execute($stmt, $position) {
		if($stmt->execute()) {
				return true;
			}

			else{
				$e = $stmt->errorInfo();
				error("Error: executing query in " . $position . ": " . $e[2]);	
				return false;
			}
	}

	/* 
	 * Create a new game entry in the DB. Returns unique game_id or
	 * -1 for error.
	 *
	 * $uid1 always makes first move
	 */
	public static function newGame($uid1,$uid2,$state,$ranked) {
	
		// Try to insert a new game
		$sql = "insert into current_games
				(uid_one
				,uid_two
				,game_state
				,turn
				,ranked)
			values
				(:uid1
				,:uid2
				,:state
				,:uid1
				,:ranked)";
		
		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid1", $uid1, PDO::PARAM_INT);
			$stmt -> bindParam(":uid2", $uid2, PDO::PARAM_INT);
			$stmt -> bindParam(":state", $state, PDO::PARAM_STR);
			$stmt -> bindParam(":ranked", $ranked, PDO::PARAM_BOOL);

			if (!Game::execute($stmt, "newGame() insert")) 
				return -1;
		}
		else {
			error("Database error in newGame() insert");
			return -1;
		}

		// If that worked, get the game id we just inserted and return it
		$sql2 = "select last_insert_id()";
		
		if ($stmt2 = $GLOBALS['db']->prepare($sql2)) {
			if (Game::execute($stmt2,"newGame() last_insert_id")) {
				$row = $stmt2->fetch();
				return $row[0];
			}
			else 
				return -1;
		}
		else {
			error("Database error in newGame() last_insert_id");
			return -1;
		}
	}

	/*
	 * Returns game state as a string. Returns null for error.
	 */
	public static function getState($gid) {

		$sql = "select game_state, turn, winner
			from current_games
			where gid = :gid";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":gid", $gid, PDO::PARAM_INT);
		
			if (is_null(Game::execute($stmt, "getState()"))) 
				return null;
	
			else {
				if ($row = $stmt->fetch())
					return $row;
				else
					return null;
			}
		}
		else  {
			error("Error accessing database in getState()");	
			return null;
		}
	}

	/*
	 * Update the game state to $state. Returns true for successful
	 * update or false for database error.
	 */ 
	public static function updateState($gid, $state, $turn) {
		$sql = "update current_games
			set game_state=:state,turn=:turn
			where gid = :gid";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam("gid",$gid, PDO::PARAM_INT);
			$stmt -> bindParam("state",$state, PDO::PARAM_STR);	
			$stmt -> bindParam("turn",$turn, PDO::PARAM_STR);			
			if (Game::execute($stmt,"updateState()")) {
				return true;
			}
			else return false;
		}
		else {
			error("Database error in updateState()");
			return false;
		}
				
	}

	/*
	 * Finish the game. Returns true for successful
	 * update or false for database error. $winUid = 0 indicates tie
	 */ 
	public static function finish($gid, $winUid) {
		$sql = "update current_games
			set winner=:uid
			where gid = :gid";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam("gid",$gid, PDO::PARAM_INT);
			$stmt -> bindParam("uid",$winUid, PDO::PARAM_INT);	
			if (Game::execute($stmt,"Game::finish()")) {
				return true;
			}
			else return false;
		}
		else {
			error("Database error in Game::finish()");
			return false;
		}
				
	}

	/* 
	 * Return game info. Currently just returns array like
	 *
	 * $array['uid_one'] => 1
	 * $array['uid_two'] => 4
 	 * 
	 * but can be expanded to include more info if necessary
	 */
	public static function info($gid) {
		$sql = "select uid_one, uid_two
			from current_games
			where gid = :gid";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam("gid",$gid, PDO::PARAM_INT);
			if (Game::execute($stmt,"info()")) {	
				if ($row = $stmt->fetch())
					return $row;
				else
					return null;
			}
			else return null;
		}
		else {
			error("Database error in info()");
			return null;
		}
	}


	/*
	public static function endGame($gid) {
		$sql = "delete from current_games
			where gid = :gid";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam("gid",$gid, PDO::PARAM_INT);
			if (Game::execute($stmt,"endGame()")) {
				return true;
			}
			else return false;
		}
		else {
			error("Database error in endGame()");
			return false;
		}
				
	}*/

	/*
	 * Returns leaderboard as a 2d array.
	 * Like ['rank'] => 1
	 * 	['uid'] => 23
	 * 	['email'] => joe@email.net
	 */
	public static function getLeaderboard() {
		$sql = 'select 
				username
				,win 
				,loss 
				,coalesce( (win/loss), 0) "ratio" 
			from users
		        order by ratio desc
			limit 10';


		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam("uid",$uid, PDO::PARAM_INT);
			if (Game::execute($stmt,"getLeaderboard()")) 
				return true;
			
			else 
				return false;
		}
		else {
			error("Database error in getLeaderboard()");
			return false;
		}

		
	}

	/*
	 * Put a user in the queue. Returns false for error.
	 */
	
	public static function enQ($uid) {
		$sql = "insert into queue (uid) values (:uid)";
	
		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam("uid",$uid, PDO::PARAM_INT);
			if (Game::execute($stmt,"enQ()")) 
				return true;
			
			else 
				return false;
		}
		else {
			error("Database error in enQ()");
			return false;
		}
	}

	
	/*
	 * Removes the user with the earliest entry date from the queue and
	 * returns his/her uid. If the the queue is empty, returns 0.
	 */
	public static function deQ() {
		$sql = "select uid from queue
			where entered = 
				(select min(entered)
				 from queue)
			limit 1";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			if (Game::execute($stmt,"deQ()")) {
				$row = $stmt->fetch(); 
				$uid = $row['uid'];
			}
			
			else 
				return false;
		}
		else {
			error("Database error in deQ()");
			return false;	
		}

		$sql = "delete from queue
			where uid = :uid";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam("uid",$uid,PDO::PARAM_INT);
			if (Game::execute($stmt,"deQ()")) {
				return $uid;
			}
			
			else 
				return false;
		}
		else {
			error("Database error in deQ()");
			return false;	
		}
	}

	/* 
	 * Return integer size of queue or -1 for error
	 */
	public static function sizeofQ() {
		$sql = "select count(*) from queue";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			if (Game::execute($stmt,"sizeofQ()")) {
				$row = $stmt->fetch(); 
				return $row[0];
			}
			
			else 
				return -1;
		}

		else {
			error("Database error in sizeofQ()");
			return -1;	
		}
	}

	/*
	 * Insert a game request from one user to another. Returns false for error. 
	 */
	public static function gameRequest($from, $to) {
		$sql = 'insert into active_reqs
			(requester
			,requested)
			
			values
			(:from
			,:to
			)

			on duplicate key
			update req_time = current_timestamp();';
			/* This last line just updates the req_time field
			   if someone makes the same request twice */
			

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('from',$from,PDO::PARAM_INT);
			$stmt->bindParam('to',$to,PDO::PARAM_INT);
			
			if (Game::execute($stmt,"gameRequest()")) 
				return true;
			
			else 
				return false;
		}
		else {
			error("Database error in gameRequest()");
			return false;
		}
	}

	/* 
	 * Return an array of uids for active requests that are requesting given UID.
	 * Array keyed by 0, 1, 2, ... etc
	 */
	public static function getRequests($uid) {
		$sql = 'select requester from active_reqs
			where requested=:uid';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$uid,PDO::PARAM_INT);
			
			if (Game::execute($stmt,"getRequests()")){
				$result = $stmt->fetchAll();
				$uids = array();
				$rownum = 0;
				foreach($result as $row) {
					$uids[$rownum] = $result[$rownum]["requester"];	
					$rownum++;
				}
				return $uids;
			}
			else return null;
		}
		else return null;
	}

	/*
	 * Return an array of all open requests made by given UID.
	 */
	public static function myRequests($uid) {
		$sql = 'select requested from active_reqs
			where requester=:uid';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$uid,PDO::PARAM_INT);
			
			if (Game::execute($stmt,"myRequests()")){
				$result = $stmt->fetchAll();
				$uids = array();
				$rownum = 0;
				foreach($result as $row) {
					$uids[$rownum] = $result[$rownum]["requested"];	
					$rownum++;
				}
				return $uids;
			}
			else return null;
		}
		else return null;
	}

	/*
	 * Remove a given request. Return false for error.
	 */
	public static function removeRequest($from, $to) {
		$sql = 'delete from active_reqs
			where requester=:from
			  and requested=:to';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('from',$from,PDO::PARAM_INT);
			$stmt->bindParam('to',$to,PDO::PARAM_INT);
			
			if (Game::execute($stmt,"removeRequest()")){
				return true;
			}
			else return false;
		}
		else return false;
	}

	
}

?>
