<?php

    require_once '../../config.php';
    require_once ROOT . '/php/db/Game.php';

    session_start();
    $uid = $_SESSION['uid'];
    
    $inq = Game::enQ($uid);
    
    if ($inq == True)
    {
        header('Location: ../searching.html');
    }
    else
    {
        echo '<!DOCTYPE HTML><html><head><title>Username Taken</title></head><body>There was an error, your not in queue <a href="../main.html">Go Back</a></body></html>';
    }
?>