<?php

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';
require_once ROOT . '/php/db/Game.php';

    session_start();
    $uid = $_SESSION['uid'];

    $currentGID = User::isInGame($uid);
    if ($currentGID == 0)
    {
        print("false");
    }
    else
    {
        $_SESSION['gid'] = $currentGID;
        print("true");
    }
?>