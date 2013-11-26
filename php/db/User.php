<?php 

class NotFoundException extends Exception {}

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(ROOT . '/php/db/Model.php');

class User
{
	/* Private variables */
	private $uid;
	private $email;

	/* Constructor */
	public function __construct($u) {
		$sql = 'select count(*) from users
			where uid = :uid';

		// if this user id does not exist in the DB throw exception
		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid", $u, PDO::PARAM_INT);
			if (Model::execute($stmt, "User constructor uid check")) {
				$result = $stmt->fetch();
				if ($result[0] == 0)
					throw new NotFoundException();
			}
			else
				throw new NotFoundException();
		}
		else {
			error("Database error in user constructor");
			throw new NotFoundException();
		}



		$this->uid = $u;
		$this->email = $this->queryEmail();
	} 

	/* Accessor functions */
	public function uid() { return $this->uid; }
	
	public function getEmail() { return $this->email; }

	/* NOTE: stats are returned as an array keyed by the stat.
  	 * It looks like this:
	 *  stats['win']  => 4
	 *  stats['loss'] => 8
	 *  stats['tie']  => 10
	 * 
  	 *  Any "meta-stats" like percentage win/loss or total games played
	 * is not provided and must be calculated
	 */
	public function getStats() {
		$sql = "select win,loss,tie from users
			where uid = :uid";
		
		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid", $this->uid, PDO::PARAM_INT);

			if (Model::execute($stmt, "User->queryEmail()")) {
				$row = $stmt->fetch();

				if (is_null($row))
					return null;
				else {
					return $row;
				}
			}
			else
				return null;
		}
		else {
			error("Database error in User->queryEmail()");
			return null;
		}
	}

	/*
	 * Creates a new user and returns an object with the new user's UID, or -1 for error.
	 * -1 will also be returned if the username is taken. 
	 * All passwords are assumed to have already been checked for validity.
         *
 	 * $username should be an email.
	 * $password should be some password in plain text. This function
	 *  will hash it.
	 */
	public static function newUser($username,$password) {
		// Check if username is taken.
		$sql0 = "select count(*) from users
			 where username = :username";

		if ($stmt = $GLOBALS['db']->prepare($sql0)) {
			$stmt -> bindParam(":username", $username, PDO::PARAM_STR);
			if (Model::execute($stmt, "newUser() check")) {
				$result = $stmt->fetch();
				if ($result[0] > 0)
					return null;
			}
			else
				return null;
		}
		else {
			error("Database error in newUser() check");
			return null;
		}
		

		/* SHA1 is not ideal for security, but this can be changed
		 * easily down the line if necessary. */
		$hashpwd = sha1($password);

		$sql = "insert into users
			(username
			,pass)
	
			values
			(:username
			,:pass)";
		
		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":username", $username, PDO::PARAM_STR);
			$stmt -> bindParam(":pass"    , $hashpwd , PDO::PARAM_STR);

			if (!Model::execute($stmt, "newUser() insert"))
				return null;
		}
		else {
			error("Database error in newUser() insert");
			return null;
		}

		// If that worked, get the UID, return it
		$sql2 = "select last_insert_id()";

		if ($stmt2 = $GLOBALS['db']->prepare($sql2)) {
			if (Model::execute($stmt2,"newUser() last_insert_id")) {
				$row = $stmt2->fetch();
				return new User($row[0]);
			}
			else 
				return null;
		}
		else {
			error("Database error in newUser() last_insert_id");
			return null;
		}
	}

	/*
	 * Authenticate a user's login credentials. 
	 * Return User object for success and null for failure.
	 */
	public static function auth($username,$password) {
		$hashpwd = sha1($password);
			
		$sql = "select uid from users
			where username = :username
			  and     pass = :pass";
		
		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":username", $username, PDO::PARAM_STR);
			$stmt -> bindParam(":pass"    , $hashpwd , PDO::PARAM_STR);

			if (Model::execute($stmt, "User::auth()")) {
				$row = $stmt->fetch();

				if (is_null($row[0])) {
					throw new NotFoundException();
					return null;
				}
				else
					return new User($row[0]);
			}
			else {
				throw new NotFoundException();	
				return null;
			}
		}
		else {
			error("Database error in User::auth()");
			throw new NotFoundException();	
			return null;
		}
		
	}

	/*
	 * The user won,lost,tied a game 
 	 * Returns true for successful update
	 */
	public function gameResult($str) {
		if ( strcmp($str,"win") && strcmp($str,"loss") && strcmp($str,"tie") ) {
			error("Invalid game result specified: " . $str);
			return false;
		}

		$sql =  "update users
		      set " . $str . " = " . $str . "+1 
		      where uid = :uid";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid", $this->uid, PDO::PARAM_INT);

			if (!Model::execute($stmt, "wonGame()")) {
					return false;
			}
			else
				return true;
		}
		else {
			error("Database error in wonGame()");
			return false;
		}
	}

	/*  Initialize the user's email
	 *  Seems handy to have this available at all times so we go ahead
	 * and populate this field every time we make a new user.
	 */
	private function queryEmail() {
		$sql = "select username from users
			where uid = :uid";
	 	
		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid", $this->uid, PDO::PARAM_INT);

			if (Model::execute($stmt, "User->queryEmail()")) {
				$row = $stmt->fetch();

				if (is_null($row[0]))
					return null;
				else
					return $row[0];
			}
			else
				return null;
		}
		else {
			error("Database error in User->queryEmail()");
			return null;
		}
	}

	/* 
	 * Called when gameResult() is called 
	 * Inserts achievements 
	 */
	private function achievementUpdate() {
		$stats = getStats();

		if ($stats['win'] >= 3)
			newAchievement(2,"Still a noob - won three games");
		if ( (double) $stats['win']/ $stats['loss'] >= 2.00 )
			newAchievement(3," win loss ratio > 2");
		if ( (double) $stats['win']/ $stats['loss'] <= 0.10 )
			newAchievement(4, " ratio < 0.1");
		if ( $stats['tie'] >= 100 )
			newAchievement(5, " tie 100 games");
	}

	/*
	 * Helper function for making achievements
	 */
	private function newAchievement($achievement_id, $achievement_text) {
		$sql = "insert into achievements
		 	(uid
			,achievement_id
			,txt)
			values
			(:uid
			,:aid
			,:txt)";

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid", $this->uid, PDO::PARAM_INT);
			$stmt -> bindParam(":aid", $achievement_id, PDO::PARAM_INT);
			$stmt -> bindParam(":txt", $achievement_text, PDO::PARAM_STR);

			if (Model::execute($stmt, "newAchievement()")) {
				$row = $stmt->fetch();

				if (is_null($row[0]))
					return null;
				else
					return $row[0];
			}
			else
				return null;
		}
		else {
			error("Database error in newAchievement()");
			return null;
		}

		
	}

	/* 
	 * Achievements reference table
	 *
	 * achievement ID  |   text 
         * -------------------------
         *             1   | "You made an account"
	 *             2   | "You won 3 games"
	 * 	       3   | "Your win loss ratio climbed above 2.00"
	 *             4   | "You suck - Your win loss ratio dipped below .10"
	 *             5   | "Tie Master - You have tied in 100 games"
	 */
}

