<?php 

require_once(dirname(dirname(__FILE__)).'/config.php');
require_once(ROOT . '\php\inc\base.php');

class Game
{
	/* 
	 * Create a new game entry in the DB. Returns unique game_id or
	 * 0 for error.
	 */
	public static function newGame($uid1,$uid2,$state) {
		$sql = "insert into current_games
				(uid_one
				,uid_two
				,game_state)
			values
				(:uid1
				,:uid2
				,:state)";
		
		if ($stmt = $db->prepare($sql)) {
			$stmt -> bindParam(":uid1", $uid1, PDO::PARAM_INT);
			$stmt -> bindParam(":uid2", $uid2, PDO::PARAM_INT);
			$stmt -> bindParam(":state", $state, PDO::PARAM_STR);
		}
		if ( $stmt -> execute()) {

			$sql2 = "select last_insert_id()";
			if ($stmt2 = $db->prepare($sql2)) {
				if ($stmt2->execute()) {
					return $stmt2;
				}
				else return 0;
			}
			else return 0;
		}
		else return 0;
	}

	/*
	 * Returns game state as a string. Returns null for error.
	 */
	public static function getState($gid) {
		$sql = "select game_state from current_games
			where game_id = :gid";

		if ($stmt = $db->prepare($sql)) {
			$stmt -> bindParam(":gid", $gid, PDO::PARAM_INT);
		
			if ($stmt->execute()) {
				return $stmt[0]['game_id'];
			}
			else
				return null;
		}
		else return null;
	}

	/*
	 * Update the game state to $state. Returns true for successful
	 * update or false for database error.
	 */ 
	public static function updateState($gid, $state) {
		$sql = "update game_state
			set state = :state
			where game_id = :gid";

		if ($stmt = $db->prepare($sql)) {
			$stmt -> bindParam("gid",$gid, PDO::PARAM_INT);
			$stmt -> bindParam("state",$state, PDO::PARAM_STR);			
			if ($stmt->execute() {
				return true;
			}
			else return false;
		}
		else return false;
				
	}

	/*
	 * Returns leaderboard as a 2d array.
	 * Like ['rank'] => 1
	 * 	['uid'] => 23
	 * 	['email'] => joe@email.net
	 */
	public static function getLeaderboard() {
	}

	/*
	 * Put a user in the queue. Returns false for error.
	 */
	
	public static function enQ($uid) {
		$sql = "insert into queue (uid) values :uid";
	
		$if ($stmt = $db->prepare($sql)) {
			$stmt -> bindParam("uid",$uid, PDO::PARAM_INT);
			if ($stmt->execute() {
				return true;
			}
			else return false;
		}
		else return false;
	}

	
	/*
	 * Removes the user with the earliest entry date from the queue and
	 * returns his uid. If the the queue is empty, returns 0.
	 */
	public static function deQ() {
		$sql = "select uid from queue
			where entered = 
				(select min(entered)
				 from queue)";

		$if ($stmt = $db->prepare($sql)) {
			if ($stmt->execute() {
				return 
			}
			else return false;
		}
		else return false;	
	}

	public static function gameRequest($from, $to) {
		
	}


}

?>
