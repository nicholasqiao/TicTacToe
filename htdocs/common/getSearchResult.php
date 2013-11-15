<?php

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

    session_start();
    $uid = $_SESSION['uid'];

    print("SEARCHING...");
    #Todo: Get whether or not you are in a game based on UID, and if you are enter that game

?>