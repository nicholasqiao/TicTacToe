<?php
    require_once '../../config.php';
    require_once ROOT . '/php/db/Game.php';
    session_start();
    if ($_REQUEST['reqid'] && $_SESSION['uid'])
    {
        $uid = $_SESSION['uid'];
        $uid2 = $_REQUEST['reqid'];
        
        $gameState = "EEEEEEEEE";
        $newGameId = Game::newGame($uid, $uid2, $gameState);
        
        Game::removeRequest($uid2, $uid);
        if ($newGameId == -1)
        {
            echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has occured with the game request <a href="./profile.html">Go Back</a></body></html>';
        }
        else
        {
            $_SESSION['gid'] = $newGameId;
        }
        header('Location: ../play.php?gid=' . $newGameId);
    }
    else 
    {
        echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has occured with the game request <a href="./profile.html">Go Back</a></body></html>';
    }
?>