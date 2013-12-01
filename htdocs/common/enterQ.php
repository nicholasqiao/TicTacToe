<?php

    require_once '../../config.php';
    require_once ROOT . '/php/db/Game.php';

    session_start();
    $uid = $_SESSION['uid'];
   
    $inq = Game::enQ($uid);
    $qSize = Game::sizeofQ();
    
    if ($qSize == 1)
    {
        header('Location: ../searching.html');
    }
    else
    {
        $firstPlayer = Game::deQ();
        $gameState = "EEEEEEEEE";
        $newGameId = Game::newGame($uid, $firstPlayer, $gameState);
        
        if ($newGameId == -1)
        {
            echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has occured, you are not in the queue <a href="../main.html">Go Back</a></body></html>';
        }
        else
        {
            $_SESSION['gid'] = $newGameId;
        }
        Game::deQ();
	    header('Location: ../play.php?gid=' . $newGameId);
    }
?>