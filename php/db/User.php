<?php 


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
				User::addAchievement($row[0],1,"You made a new account!");
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

	public static function recordWin($uid) {
		$sql = 'update users set win=win+1 where uid=:uid';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid", $uid, PDO::PARAM_INT);

			if (Model::execute($stmt, "recordWin()")) {
				User::updateAchievements($uid);
				return true;
			}
			else
				return false;
		}
		else {
			error("Database error in recordWin()");
			return false;
		}
	}

	public static function recordLoss($uid) {
		$sql = 'update users set loss=loss+1 where uid=:uid';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid", $uid, PDO::PARAM_INT);

			if (Model::execute($stmt, "recordLoss()")) {
				User::updateAchievements($uid);
				return true;
			}
			else
				return false;
		}
		else {
			error("Database error in recordLoss()");
			return false;
		}
	}

	public static function recordTie($uid) {
	$sql = 'update users set tie=tie+1 where uid=:uid';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":uid", $uid, PDO::PARAM_INT);

			if (Model::execute($stmt, "recordTie()")) {
				User::updateAchievements($uid);
				return true;
			}
			else
				return false;
		}
		else {
			error("Database error in recordTie()");
			return false;
		}
	}

	/*
	 * Insert a game request from one user to another. Returns false for error. 
	 */
	public function gameRequest($to) {
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
			$stmt->bindParam('from',$this->uid,PDO::PARAM_INT);
			$stmt->bindParam('to',$to,PDO::PARAM_INT);
			
			if (Model::execute($stmt,"gameRequest()")) 
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
	public function getRequests() {
		$sql = 'select a.requester
	  		      ,u.username
	  		from active_reqs a 
			join users u on a.requester = u.uid
			where a.requested = ' . $this->uid .';';
            
            if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$this->uid,PDO::PARAM_INT);
            if (Model::execute($stmt,"getRequests()")){
				if ($result = $stmt->fetchAll())
					return $result;
				else
					return null;
            }
			else return null;
		}
		else return null;
	}

	/* 
	 * returns gid or 0 for error
	 *
	 * uses 'limit 1' - may cause issues if the player manages to get 
	 * into more than one game at a time
	 */
	public static function isInGame($uid) {
		$sql = 'select gid from current_games
			where    (uid_one = :uid
			   or	  uid_two = :uid)
			  and winner is null
			limit 1';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$uid,PDO::PARAM_INT);
			
			if (Model::execute($stmt,"getRequests()")){
				if ($row = $stmt->fetch())
					return $row['gid'];
				else
					return 0;
			}
			else return 0;
		}
		else return 0;
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

	/*
	 * called from record{Win,Loss,Tie}
 	 *
	 * checks new user stats and greedily adds new achievements
	 */
	private static function updateAchievements($uid) {
		$user = new User($uid);
		$stats = $user->getStats();

		if ($stats['win'] >= 3)
			User::addAchievement($uid,2,"You won 3 games!");

		$ratio = ((double) $stats['win']) / $stats['loss'];
		if ( $ratio > 2.0)
			User::addAchievement($uid,3,"Your win-loss ratio climbed above 2.00!");

		if ( $ratio < 0.1)
			User::addAchievement($uid,4,"You suck! Your win loss ratio dipped below 0.10");

		if ( $stats['tie'] >= 100 ) 
			User::addAchievement($uid,5,"You have tied in 100 games!");

	}

	private static function addAchievement ($uid, $achievementNum, $achievementText) {
		$sql = 'insert into achievements
			(uid
			,achievement_id
			,txt)
			values
			(:uid
			,:achievementid
			,:achievementtext)';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$uid,PDO::PARAM_INT);
			$stmt->bindParam('achievementid',$achievementNum,PDO::PARAM_INT);
			$stmt->bindParam('achievementtext',$achievementText,PDO::PARAM_STR);
			
			if (Model::execute($stmt,"User::addAchievement")){
					return;
			}
			else
				error("Database error in User::addAchievement");
		}
	}

	/*
	 * Returns rows that indicate a user's achievements.
	 */
	public function achievements() {
		$sql = 'select achievement_id, txt
			  from achievements	
			  where uid = :uid';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$this->uid,PDO::PARAM_INT);

			if (Model::execute($stmt,"User->achievements")) {
				if ($result = $stmt->fetchAll())
					return $result;
				else
					return null;
			}
			else {
				error("DB error User->achievements");
				return null;
			}
		}
		
	}

	/* 
	 * friend list stuff
 	 */

	// return false for error
	public function addFriend($friend) {
		$sql = 'insert into friendlist
				(uid
				,friend)
			values
				(:uid
				,:friend)';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$this->uid,PDO::PARAM_INT);
			$stmt->bindParam('friend',$friend,PDO::PARAM_INT);

			if (Model::execute($stmt,"User->addFriend")) {
				return true;
			}
			else {
				error("DB error User->addFriend");
				return false;
			}
		}
		else 
			return false;
	}

	// return false for error
	public function delFriend($friend) {
		$sql = 'delete from friendlist
			where    uid = :uid
			  and friend = :friend';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$this->uid,PDO::PARAM_INT);
			$stmt->bindParam('friend',$friend,PDO::PARAM_INT);

			if (Model::execute($stmt,"User->delFriend")) {
				return true;
			}
			else {
				error("DB error User->delFriend");
				return false;
			}
		}
		else 
			return false;
	}

	// get friends list, return null for none or error
	public function friends() {
		$sql = 'select f.friend "uid"
	  		      ,u.username
			from friendlist f
			join users u on f.friend = u.uid
			where f.uid = 4';

		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt->bindParam('uid',$this->uid,PDO::PARAM_INT);

			if (Model::execute($stmt,"User->friends")) {
				if ($result = $stmt->fetchAll())
					return $result;
				else 
					return null;
			}
			else {
				error("DB error User->friends");
				return null;
			}
		}
		else 
			return null;
	}
		
			
}

