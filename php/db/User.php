<?php 

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(ROOT . '/php/inc/base.php');

class User
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
	 * Creates a new user and returns the new UID, or -1 for error.
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
			if (User::execute($stmt, "newUser() check")) {
				$result = $stmt->fetch();
				if ($result[0] > 0)
					return -1;
			}
			else
				return -1;
		}
		else {
			error("Database error in newUser() check");
			return -1;
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

			if (!User::execute($stmt, "newUser() insert"))
				return -1;
		}
		else {
			error("Database error in newUser() insert");
			return -1;
		}

		// If that worked, get the UID, return it
		$sql2 = "select last_insert_id()";

		if ($stmt2 = $GLOBALS['db']->prepare($sql2)) {
			if (User::execute($stmt2,"newUser() last_insert_id")) {
				$row = $stmt2->fetch();
				return $row[0];
			}
			else 
				return -1;
		}
		else {
			error("Database error in newUser() last_insert_id");
			return -1;
		}
	}

	/*
	 * Authenticate a user's login credentials. Return UID for success and
	 * -1 for failure.
	 */
	public static function auth($username,$password) {
		$hashpwd = sha1($password);
			
		$sql = "select uid from users
			where username = :username
			  and     pass = :pass";
		
		if ($stmt = $GLOBALS['db']->prepare($sql)) {
			$stmt -> bindParam(":username", $username, PDO::PARAM_STR);
			$stmt -> bindParam(":pass"    , $hashpwd , PDO::PARAM_STR);

			if (User::execute($stmt, "User::auth()")) {
				$row = $stmt->fetch();

				if (is_null($row))
					return -1;
				else
					return $row[0];
			}
			else
				return -1;
		}
		else {
			error("Database error in User::auth()");
			return -1;
		}
		
	}		

}

