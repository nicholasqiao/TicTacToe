<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(ROOT . '/php/inc/base.php');

class Model
{

	/*
 	 * Helper function that executes input statment $stmt.
	 * Cleans up the code below a little bit; allows for easier error logging.
	 */
	public static function execute($stmt, $position) {
		if($stmt->execute()) {
				return true;
			}

			else{
				$e = $stmt->errorInfo();
				error("Error: executing query in " . $position . ": " . $e[2]);	
				return false;
			}
	}
}


?>
