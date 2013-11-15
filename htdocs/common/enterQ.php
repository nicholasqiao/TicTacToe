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
        echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has occured, you are not in the queue <a href="../main.html">Go Back</a></body></html>';
    }
?>