<?php 
/* Contains info about how to connect to the database.
 * Include this file to any files that use PDO to access the DB.
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

define ("DB_USER", "root");
define ("DB_PASS", "admin");
define ("DB_NAME", "wombat");
define ("DB_HOST", "localhost");

global $db;

  // Create a database object
    try {
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
        $db = new PDO($dsn, DB_USER, DB_PASS, array(
		PDO::ATTR_PERSISTENT => true));
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
	error("Connection failed: " . $e->getMessage());
        exit;
    }

?>
