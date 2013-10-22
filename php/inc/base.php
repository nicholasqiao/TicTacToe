<?php 
/* Contains info about how to connect to the database.
 * Include this file to any files that use PDO to access the DB.
 */

define ("DB_USER", "root");
define ("DB_PASS", "admin");
define ("DB_DATABASE", "wombat");
define ("DB_HOST", "localhost");

  // Create a database object
    try {
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
        $db = new PDO($dsn, DB_USER, DB_PASS, array(
		PDO::ATTR_PERSISTENT => true));
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }

?>
